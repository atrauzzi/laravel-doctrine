<?php namespace Atrauzzi\LaravelDoctrine\Support\Facades;

use Illuminate\Support\Facades\Facade;


class Doctrine extends Facade {

	/**
	* Get the registered name of the component.
	*
	* @return string
	*/
	protected static function getFacadeAccessor() { return 'doctrine'; }

}