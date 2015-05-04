<?php  namespace Atrauzzi\LaravelDoctrine\CacheProvider;

use Doctrine\Common\Cache\MemcacheCached;

/**
 * Class MemcachedProvider
 * @package Atrauzzi\LaravelDoctrine\CacheProvider
 */
class MemcachedProvider extends CacheProvider{

    protected static $required_configurations = ['host','port'];

    /**
     * Sets up and returns the CacheProvider
     * @param $config
     * @return \Doctrine\Common\Cache\CacheProvider
     */
    protected static function initialize($config)
    {
        $memcached = new \Memcached();
        $memcached->addserver($config['host'], $config['port']);
        $cache = new MemcachedCache();
        $cache->setMemcached($memcached);

        return $cache;
    }
}