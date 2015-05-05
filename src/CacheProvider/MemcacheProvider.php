<?php  namespace Atrauzzi\LaravelDoctrine\CacheProvider;

use Doctrine\Common\Cache\MemcacheCache;

/**
 * Class MemcacheProvider
 * @package Atrauzzi\LaravelDoctrine\CacheProvider
 */
class MemcacheProvider extends CacheProvider{

    protected static $required_configurations = ['host','port'];

    /**
     * Sets up and returns the CacheProvider
     * @param $config
     * @return \Doctrine\Common\Cache\CacheProvider
     */
    protected function initialize($config)
    {
        $memcache = new \Memcache();
        $memcache->connect($config['host'], $config['port']);
        $cache = new MemcacheCache();
        $cache->setMemcache($memcache);

        return $cache;
    }
}