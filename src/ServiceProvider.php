<?php namespace Atrauzzi\LaravelDoctrine {

	use Illuminate\Support\ServiceProvider as Base;
	//
	use Doctrine\DBAL\Types\Type;
	use Illuminate\Contracts\Foundation\Application;
	use Doctrine\ORM\Configuration as DoctrineConfig;
	use Doctrine\Common\EventManager;
	use Doctrine\ORM\Tools\Setup;
	use Doctrine\ORM\Events;
	use Doctrine\ORM\EntityManager;
	use Doctrine\ORM\Tools\SchemaTool;
	use RuntimeException;


	class ServiceProvider extends Base {

		/**
		 * Bootstrap the application events.
		 *
		 * @return void
		 */
		public function boot() {

			Type::addType('json', '\Atrauzzi\LaravelDoctrine\Type\Json');

			$this->app->singleton('Doctrine\ORM\EntityManager', function (Application $app) {

				$debug = config('doctrine.debug', config('app.debug', false));

				$cache = $debug ? null : $this->createCache();

				$doctrineConfig = Setup::createConfiguration(
					$debug,
					config('doctrine.proxy_classes.directory', storage_path('doctrine/proxies')),
					// Note: Don't worry, caches are configured below.
					null
				);

				$metadataConfig = config('doctrine.metadata', [
					'driver' => 'config'
				]);

				if(!empty($metadataConfig) && is_array($metadataConfig[0])) {

					$metadataDriver = new \Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain();

					foreach($metadataConfig as $subDriverConfig) {

						if(!is_array($subDriverConfig))
							continue;

						$metadataDriver->addDriver(
							$this->createMetadataDriver($doctrineConfig, $subDriverConfig),
							array_get($subDriverConfig, 'namespace', 'App')
						);

					}

				}
				else {
					$metadataDriver = $this->createMetadataDriver($doctrineConfig, $metadataConfig);
				}

				$doctrineConfig->setMetadataDriverImpl($metadataDriver);

				// Note: These must occur after Setup::createAnnotationMetadataConfiguration() in order to set custom namespaces properly
				if($cache) {
					$doctrineConfig->setMetadataCacheImpl($cache);
					$doctrineConfig->setQueryCacheImpl($cache);
					$doctrineConfig->setResultCacheImpl($cache);
				}

				$doctrineConfig->setAutoGenerateProxyClasses(config('doctrine.proxy_classes.auto_generate', !$debug));
				$doctrineConfig->setDefaultRepositoryClassName(config('doctrine.default_repository', '\Doctrine\ORM\EntityRepository'));
				$doctrineConfig->setSQLLogger(config('doctrine.sql_logger'));

				if($proxyClassNamespace = config('doctrine.proxy_classes.namespace'))
					$doctrineConfig->setProxyNamespace($proxyClassNamespace);

				// Trap doctrine events, to support entity table prefix
				$eventManager = new EventManager();
				if($prefix = config('doctrine.connection.prefix'))
					$eventManager->addEventListener(Events::loadClassMetadata, new Listener\Metadata\TablePrefix($prefix));

				//
				// At long last!
				//
				return EntityManager::create(config('doctrine.connection'), $doctrineConfig, $eventManager);

			});

			$this->app->singleton('Doctrine\ORM\Tools\SchemaTool', function (Application $app) {
				return new SchemaTool($app['Doctrine\ORM\EntityManager']);
			});

			$this->app->singleton('Doctrine\ORM\Mapping\ClassMetadataFactory', function (Application $app) {
				return $app->make('Doctrine\ORM\EntityManager')->getMetadataFactory();
			});

			$this->app->singleton('Doctrine\Common\Persistence\ManagerRegistry', function (Application $app) {
				$connections = ['doctrine.connection'];
				$managers = ['doctrine' => 'doctrine'];
				$proxy = 'Doctrine\Common\Persistence\Proxy';
				return new DoctrineRegistry('doctrine', $connections, $managers, $connections[0], $managers['doctrine'], $proxy);
			});

			$this->app->singleton('doctrine.connection', function (Application $app) {
				return $app->make('Doctrine\ORM\EntityManager')->getConnection();
			});

		}

		/**
		 * Register the service provider.
		 *
		 * @return void
		 */
		public function register() {

			$this->publishes([
				__DIR__ . '/../config/doctrine.php' => config_path('doctrine.php')
			]);

			$this->commands([
				'Atrauzzi\LaravelDoctrine\Console\CreateSchemaCommand',
				'Atrauzzi\LaravelDoctrine\Console\UpdateSchemaCommand',
				'Atrauzzi\LaravelDoctrine\Console\DropSchemaCommand'
			]);

		}

		/**
		 * Get the services provided by the provider.
		 *
		 * @return array
		 */
		public function provides() {
			return [
				'Doctrine\ORM\EntityManager',
				'Doctrine\ORM\Mapping\ClassMetadataFactory',
				'Doctrine\ORM\Tools\SchemaTool',
			];
		}

		//
		//
		//

		/**
		 * Takes care of building any drivers we wish to support.
		 *
		 * Note: Chain is handled above, it's special.
		 *
		 * @param DoctrineConfig $config
		 * @param array $driverConfig
		 * @return \Doctrine\Common\Persistence\Mapping\Driver\MappingDriver
		 */
		protected function createMetadataDriver(DoctrineConfig $config, $driverConfig) {

			switch($driver = array_get($driverConfig, 'driver')) {

				case 'config':
					return new ConfigMappingDriver();
				break;

				case 'annotation':
					return $config->newDefaultAnnotationDriver(
						array_get($driverConfig, 'paths', app_path()),
						array_get($driverConfig, 'simple', false)
					);
				break;

				case null:
					throw new RuntimeException('Metadata driver has unspecified type.');
				break;

				default:
					throw new RuntimeException(sprintf('Unsupported driver: %s', $driver));
				break;

			}

		}

		/**
		 * Selects the best caching implementation for the current environment.
		 *
		 * @return \Doctrine\Common\Cache\CacheProvider|null
		 */
		protected function createCache() {

			$cacheProvider = config('doctrine.cache.provider', config('cache.default'));

			switch($cacheProvider) {

				case 'memcache':

					$memcache = new \Memcache();
					$memcache->connect(
						config('doctrine.cache.memcache.host'),
						config('doctrine.cache.memcache.port')
					);

					$cache = new \Doctrine\Common\Cache\MemcacheCache();

					$cache->setMemcache($memcache);

				break;

				case 'memcached':

					$memcache = new \Memcached();
					$memcache->addServer(
						config('doctrine.cache.memcached.host', config('cache.stores.memcached.0.host')),
						config('doctrine.cache.memcached.port', config('cache.stores.memcached.0.port'))
					);

					$cache = new \Doctrine\Common\Cache\MemcachedCache();

					$cache->setMemcached($memcache);

				break;

				case 'couchbase':

					$couchbase = new \Couchbase(
						config('doctrine.cache.couchbase.hosts'),
						config('doctrine.cache.couchbase.user'),
						config('doctrine.cache.couchbase.password'),
						config('doctrine.cache.couchbase.bucket'),
						config('doctrine.cache.couchbase.persistent')
					);

					$cache = new \Doctrine\Common\Cache\CouchbaseCache();

					$cache->setCouchbase($couchbase);

				break;

				case 'redis':

					$redis = new \Redis();
					$redis->connect(
						config('doctrine.cache.redis.host', config('database.redis.default.host')),
						config('doctrine.cache.redis.port', config('database.redis.default.port'))
					);

					if($database = config('doctrine.cache.redis.database', config('database.redis.default.database')))
						$redis->select($database);

					$cache = new \Doctrine\Common\Cache\RedisCache();

					$cache->setRedis($redis);

				break;

				case 'apc':
					$cache = new \Doctrine\Common\Cache\ApcCache();
				break;

				case 'xcache':
					$cache = new \Doctrine\Common\Cache\XcacheCache();
				break;

				default:
					$cache = new \Doctrine\Common\Cache\ArrayCache();
				break;

			}

			if(
				$cache instanceof \Doctrine\Common\Cache\CacheProvider
				&& $namespace = config('doctrine.cache.namespace', config('cache.prefix'))
			)
				$cache->setNamespace($namespace);

			return $cache;

		}

	}

}
