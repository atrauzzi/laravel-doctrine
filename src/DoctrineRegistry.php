<?php namespace Atrauzzi\LaravelDoctrine;

use Doctrine\Common\Persistence\AbstractManagerRegistry;

/**
 * Implementation of the Doctrine ManagerRegistry interface.
 * Provides easier integration with third party libraries such as
 * DoctrineBridge (https://github.com/symfony/DoctrineBridge).
 */
class DoctrineRegistry extends AbstractManagerRegistry {

	/**
	 * Returns the service name, for our purposes this will
	 * almost always return the Doctrine facade, our access to the
	 * entity manager.
	 */
	public function getService($name) {
		return app($name);
	}

	/**
	 * @param string $name
	 */
	public function resetService($name) {
		app()->forgetInstance($name);
	}

	/**
	 * @param string $namespaceAlias
	 * @return string|void
	 */
	public function getAliasNamespace($namespaceAlias) {
	}

	/**
	 * @param string $class
	 * @return mixed|object
	 */
	public function getManagerForClass($class) {
		return $this->getService('doctrine');
	}

}
