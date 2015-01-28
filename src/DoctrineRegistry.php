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
    return \App::make($name);
  }

  /**
   * Essentially sets the entity manager to null in the ioc.
   */
  public function resetService($name) {
    $app = app();
    $app[$name] = null;
  }

  public function getAliasNamespace($namespaceAlias) {}

  /**
   * TODO This will have to adjusted at some point if we need to implement
   * multiple entity managers.
   */
  public function getManagerForClass($class) {
    return $this->getService('doctrine');
  }
}
