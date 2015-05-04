<?php namespace Atrauzzi\LaravelDoctrine {

	use Illuminate\Support\ServiceProvider as Base;
	use Doctrine\DBAL\Types\Type;
	use Illuminate\Contracts\Foundation\Application;
	use Doctrine\ORM\Configuration as DoctrineConfig;
	use Doctrine\Common\EventManager;
	use Doctrine\ORM\Tools\Setup;
	use Doctrine\ORM\Events;
	use Doctrine\ORM\EntityManager;
	use Doctrine\ORM\Tools\SchemaTool;
	use RuntimeException;
	use Doctrine\ORM\Mapping\Driver\YamlDriver;
	use Doctrine\ORM\Mapping\Driver\XmlDriver;


	class ServiceProvider extends Base {

		/**
		 * Bootstrap the application events.
		 *
		 * @return void
		 */
		public function boot() {

            $this->registerCustomTypes();

            $this->publishes([__DIR__ .'/..'. '/config/doctrine.php'=> config_path('doctrine.php')], 'config');

            $this->commands([
				'Atrauzzi\LaravelDoctrine\Console\CreateSchemaCommand',
				'Atrauzzi\LaravelDoctrine\Console\UpdateSchemaCommand',
				'Atrauzzi\LaravelDoctrine\Console\DropSchemaCommand'
			]);
		}

		/**
		 * Register the service provider.
		 *
		 * @return void
		 */
		public function register() {

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

				if(!empty($metadataConfig) && isset($metadataConfig[0]) && is_array($metadataConfig[0])) {

					$metadataDriver = new \Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain();

					foreach($metadataConfig as $subDriverConfig) {

						if(!is_array($subDriverConfig))
							continue;

						$metadataDriver->addDriver(
							$this->createMetadataDriver($doctrineConfig, $subDriverConfig),
							array_get($subDriverConfig, 'namespace', 'App')
						);
						if (isset($subDriverConfig['namespace']) && isset($subDriverConfig['alias'])) {
							$doctrineConfig->addEntityNamespace($subDriverConfig['alias'],$subDriverConfig['namespace']);
						}
					}

				}
				else {
					$metadataDriver = $this->createMetadataDriver($doctrineConfig, $metadataConfig);
				}

				$doctrineConfig->setMetadataDriverImpl($metadataDriver);

				//add in trig functions to doctrine for mysql
				$doctrineConfig->setCustomNumericFunctions(array(
					'ACOS'    => 'DoctrineExtensions\Query\Mysql\Acos',
					'ASIN'    => 'DoctrineExtensions\Query\Mysql\Asin',
					'ATAN'    => 'DoctrineExtensions\Query\Mysql\Atan',
					'ATAN2'   => 'DoctrineExtensions\Query\Mysql\Atan2',
					'COS'     => 'DoctrineExtensions\Query\Mysql\Cos',
					'COT'     => 'DoctrineExtensions\Query\Mysql\Cot',
					'DEGREES' => 'DoctrineExtensions\Query\Mysql\Degrees',
					'RADIANS' => 'DoctrineExtensions\Query\Mysql\Radians',
					'SIN'     => 'DoctrineExtensions\Query\Mysql\Sin',
					'TAN'     => 'DoctrineExtensions\Query\Mysql\Tan'
				));

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

				case 'yaml':
					return new YamlDriver(array_get($driverConfig, 'paths', app_path()));
				break;

				case 'xml':
					return new XmlDriver(array_get($driverConfig, 'paths', app_path()));
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
			$cacheProvider = config('doctrine.cache.provider', config('cache.default','Array'));
            $namespace = config('doctrine.cache.namespace', config('cache.prefix'));

            $cache = CacheFactory::getInstance($cacheProvider, $namespace);

            return $cache;
		}

        protected function registerCustomTypes()
        {
            foreach(config('doctrine.custom_types',array()) as $name=>$class)
            {
                Type::addType($name, $class);
            }
        }

    }

}
