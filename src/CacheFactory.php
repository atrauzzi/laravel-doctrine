<?php namespace Atrauzzi\LaravelDoctrine;

use Psy\Exception\ErrorException;
use Symfony\Component\Debug\Exception\ClassNotFoundException;

/**
 * Class CacheFactory
 * @package Atrauzzi\LaravelDoctrine
 */
class CacheFactory {

    protected static $supportedProviders = [];

    /**
     * @param $type
     * @param $namespace
     * @return \Doctrine\Common\Cache\CacheProvider
     * @throws \Symfony\Component\Debug\Exception\ClassNotFoundException
     * @throws \Exception
     */
    public static function getCacheProvider($type, $configuration, $namespace = null, $providers = [])
    {
        $providers = array_merge(static::$supportedProviders, $providers);

        if (! array_key_exists($type, $providers))
        {
            throw new \RuntimeException('Unsupported Doctrine cache provider specified: ' . $type . '. Check your configuration.');
        }

        if (class_exists($providers[$type]))
        {
            $cache = $providers[$type]::getCacheProvider($configuration);
        } else
        {
            throw new ClassNotFoundException('Class not found [' . $providers[$type] . ']', new \ErrorException());
        }

        $cache->setNamespace($namespace);

        return $cache;
    }

    public static function setProviders($providersArray)
    {
        static::$supportedProviders = $providersArray;
    }
}
