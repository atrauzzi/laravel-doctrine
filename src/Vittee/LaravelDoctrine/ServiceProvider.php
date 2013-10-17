<?php namespace Vittee\LaravelDoctrine;

use Atrauzzi\LaravelDoctrine\LaravelDoctrineServiceProvider;

class ServiceProvider extends LaravelDoctrineServiceProvider {
	
	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot() {
		$this->package('vittee/laravel-doctrine');
	}

}

