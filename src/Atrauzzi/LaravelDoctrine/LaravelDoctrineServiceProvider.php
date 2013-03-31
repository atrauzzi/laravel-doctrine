<?php namespace Atrauzzi\LaravelDoctrine;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;

use Atrauzzi\LaravelDoctrine\Console\CreateSchemaCommand;

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

		// Assign our EntityManager to the 'doctrine' key in the IoC container.
		App::singleton('doctrine', function ($app) {

			// Retrieve our configuration.
			$connection = Config::get('laravel-doctrine::doctrine.connection');
			$config = Setup::createAnnotationMetadataConfiguration(
				Config::get('laravel-doctrine::doctrine.metadata'),
				App::environment() == 'development'
			);

			// Obtain an EntityManager from Doctrine.
			return EntityManager::create($connection, $config);

		});

	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {

		// Utilities
		App::bind('doctrine.schema-tool', function ($app) {
			return new SchemaTool(App::make('doctrine'));
		});

		// Commands
		App::bind('doctrine.schema.create', function ($app) {
			return new CreateSchemaCommand(App::make('doctrine'));
		});
		$this->commands(
			'doctrine.schema.create'
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