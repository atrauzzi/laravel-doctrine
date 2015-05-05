<?php  namespace Atrauzzi\LaravelDoctrine\CacheProvider;

use Doctrine\Common\Cache\RedisCache;

/**
 * Class MemcachedProvider
 * @package Atrauzzi\LaravelDoctrine\CacheProvider
 */
class RedisProvider extends CacheProvider{

    protected static $required_configurations = ['host','port','database'];

    /**
     * Sets up and returns the CacheProvider
     * @param $config
     * @return \Doctrine\Common\Cache\CacheProvider
     */
    protected function initialize($config)
    {
        $redis = new \Redis();
        $redis->connect($config['host'],$config['port']);
        $redis->select($config['database']);
        $cache = new RedisCache();
        $cache->setRedis($redis);

        return $cache;
    }
}