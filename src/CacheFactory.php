<?php namespace Atrauzzi\LaravelDoctrine;

use Psy\Exception\ErrorException;
use Symfony\Component\Debug\Exception\ClassNotFoundException;

/**
 * Class CacheFactory
 * @package Atrauzzi\LaravelDoctrine
 */
class CacheFactory {

    /**
     * @param $type
     * @param $namespace
     * @return \Doctrine\Common\Cache\CacheProvider
     * @throws \Symfony\Component\Debug\Exception\ClassNotFoundException
     * @throws \Exception
     */
    public static function getCacheProvider($type, $namespace = null)
    {
        $providers = config('doctrine.cache.providers');
        if (! array_key_exists($type, $providers))
        {
            throw new \RuntimeException('Unsupported Doctrine cache provider specified: ' . $type . '. Check your configuration.');
        }

        if (class_exists($providers[$type]))
        {
            $cache = $providers[$type]::getCacheProvider(config('doctrine.cache.' . $type));
        } else
        {
            throw new ClassNotFoundException('Class not found [' . $providers[$type] . ']', new \ErrorException());
        }

        $cache->setNamespace($namespace);

        return $cache;
    }
}
