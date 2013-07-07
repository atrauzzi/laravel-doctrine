<?php namespace Atrauzzi\LaravelDoctrine;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use Doctrine\ORM\Tools\Setup;
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
			$config = Setup::createAnnotationMetadataConfiguration(
				Config::get('laravel-doctrine::doctrine.metadata'),
				App::environment() == 'development',
				Config::get('laravel-doctrine::doctrine.proxy_classes.directory')
			);
			
			$proxy_class_namespace = Config::get('laravel-doctrine::doctrine.proxy_classes.namespace');
			if ($proxy_class_namespace !== null) {
				$config->setProxyNamespace($proxy_class_namespace);
			}
			
			// Obtain an EntityManager from Doctrine.
			return EntityManager::create($connection, $config);
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
