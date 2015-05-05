<?php  namespace Atrauzzi\LaravelDoctrine\CacheProvider;

abstract class CacheProvider {

    protected static $required_configurations = [];


    protected function __construct()
    {
    }

    /**
     * @param $config
     * @return \Doctrine\Common\Cache\CacheProvider
     * @throws \Exception
     */
    public static function getCacheProvider($config)
    {
        if(!static::hasValidParameters($config))
        {
            throw new \InvalidArgumentException('Missing one or more required parameters ['.implode(', ',static::$required_configurations).']');
        }

        //return static::initialize($config);
        return (new static)->initialize($config);
    }

    protected static function hasValidParameters($params)
    {
        if(count(static::$required_configurations) <= 0) return true;

        $params = is_null($params) ? [] : $params;
        return count(array_intersect_key(array_flip(static::$required_configurations), $params)) === count(static::$required_configurations);
    }

    /**
     * Sets up and returns the CacheProvider
     * @param $config
     * @return \Doctrine\Common\Cache\CacheProvider
     */
    abstract protected function initialize($config);
}