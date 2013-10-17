<?php namespace Atrauzzi\LaravelDoctrine;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use Doctrine\Common\EventManager;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\Events;
use Doctrine\ORM\EntityManager;

class LaravelDoctrineServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot() {
		$this->package('atrauzzi/laravel-doctrine');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {

		//
		// Doctrine
		//
		App::singleton('doctrine', function ($app) {
			// Retrieve our configuration.
			$connection = Config::get('laravel-doctrine::doctrine.connection');
			$isDevMode = in_array(App::environment(), array('development', 'local'));

			$cache = null; // default, Let Doctrine decide

			if (!$isDevMode) {
				$cache_config = Config::get('laravel-doctrine::doctrine.cache');
				$cache_provider = $cache_config['provider'];
				$cache_provider_config = $cache_config[$cache_provider];

				switch ($cache_provider) {
					case 'apc':
						if (extension_loaded('apc')) {
							$cache = new \Doctrine\Common\Cache\ApcCache();
						}						
						break;

					case 'xcache':
						if (extension_loaded('xcache')) {
							$cache = new \Doctrine\Common\Cache\XcacheCache();
						}
						break;

					case 'memcache':
						if (extension_loaded('memcache')) {
							$memcache = new \Memcache();
							$memcache->connect('127.0.0.1');
							$cache = new \Doctrine\Common\Cache\MemcacheCache();
							$cache->setMemcache($memcache);
						}
						break;

					case 'redis':
						if (extension_loaded('redis')) {
							$redis = new \Redis();
							$redis->connect($cache_provider_config['host'], $cache_provider_config['port']);

							if ($cache_provider_config['database']) {
								$redis->select($cache_provider_config['database']);
							}

							$cache = new \Doctrine\Common\Cache\RedisCache();
							$cache->setRedis($redis);

						}
						break;
				}
			}
			

			$config = Setup::createAnnotationMetadataConfiguration(
				Config::get('laravel-doctrine::doctrine.metadata'),
				$isDevMode,
				Config::get('laravel-doctrine::doctrine.proxy_classes.directory'),
				$cache
			);
			
			$config->setAutoGenerateProxyClasses(
				Config::get('laravel-doctrine::doctrine.proxy_classes.auto_generate')
			);
			
			$proxy_class_namespace = Config::get('laravel-doctrine::doctrine.proxy_classes.namespace');
			if ($proxy_class_namespace !== null) {
				$config->setProxyNamespace($proxy_class_namespace);
			}

			// Trap doctrine event, to support entity table prefix
			$evm = new EventManager();

			if (isset($connection['prefix']) && !empty($connection['prefix'])) {		
				$evm->addEventListener(Events::loadClassMetadata, new Listener\Metadata\TablePrefix($connection['prefix']));
			}
			
			// Obtain an EntityManager from Doctrine.
			return EntityManager::create($connection, $config, $evm);
		});

		//
		// Utilities
		//
		App::singleton('doctrine.metadata-factory', function ($app) {
			return App::make('doctrine')->getMetadataFactory();
		});
		App::singleton('doctrine.metadata', function ($app) {
			return App::make('doctrine.metadata-factory')->getAllMetadata();
		});
		App::bind('doctrine.schema-tool', function ($app) {
			return new \Doctrine\ORM\Tools\SchemaTool(App::make('doctrine'));
		});

		//
		// Commands
		//
		App::bind('doctrine.schema.create', function ($app) {
			return new \Atrauzzi\LaravelDoctrine\Console\CreateSchemaCommand(App::make('doctrine'));
		});
		App::bind('doctrine.schema.update', function ($app) {
			return new \Atrauzzi\LaravelDoctrine\Console\UpdateSchemaCommand(App::make('doctrine'));
		});
		App::bind('doctrine.schema.drop', function ($app) {
			return new \Atrauzzi\LaravelDoctrine\Console\DropSchemaCommand(App::make('doctrine'));
		});
		$this->commands(
			'doctrine.schema.create',
			'doctrine.schema.update',
			'doctrine.schema.drop'
		);

	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides() {
		return array();
	}

}
