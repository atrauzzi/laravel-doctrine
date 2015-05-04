<?php  namespace Atrauzzi\LaravelDoctrine\CacheProvider;

abstract class CacheProvider {

    protected static $required_configurations = [];

    /**
     * @param $config
     * @return \Doctrine\Common\Cache\CacheProvider
     * @throws \Exception
     */
    public static function getCacheProvider($config)
    {
        if(self::hasValidParameters($config))
        {
            throw new \InvalidArgumentException('Missing one or more required parameters ['.implode(', ',self::$required_configurations).']');
        }

        return self::initialize($config);
    }

    protected static function hasValidParameters($params)
    {
        return count(array_intersect(array_flip(static::$required_configurations), $params)) !== count(static::$required_configurations);
    }

    /**
     * Sets up and returns the CacheProvider
     * @param $config
     * @return \Doctrine\Common\Cache\CacheProvider
     */
    abstract protected static function initialize($config);
}