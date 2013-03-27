<?php namespace Atrauzzi\LaravelDoctrine;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
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

		// Retrieve our configuration.
		$connection = Config::get('laravel-doctrine::doctrine.connection');
		$config = Setup::createAnnotationMetadataConfiguration(
			Config::get('laravel-doctrine::doctrine.metadata'),
			App::environment() == 'development'
		);

		// Obtain an EntityManager from Doctrine.
		$entityManager = EntityManager::create($connection, $config);

		// Assign our EntityManager to the 'doctrine' key in the IoC container.
		$this->app['doctrine'] = $this->app->share(function () use ($entityManager) {
			return $entityManager;
		});

	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {
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