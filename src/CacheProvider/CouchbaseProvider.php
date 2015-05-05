<?php  namespace Atrauzzi\LaravelDoctrine\CacheProvider;

use Doctrine\Common\Cache\CouchbaseCache;

/**
 * Class MemcachedProvider
 * @package Atrauzzi\LaravelDoctrine\CacheProvider
 */
class CouchbaseProvider extends CacheProvider{

    protected static $required_configurations = ['hosts','user','password','bucket','persistent'];

    /**
     * Sets up and returns the CacheProvider
     * @param $config
     * @return \Doctrine\Common\Cache\CacheProvider
     */
    protected function initialize($config)
    {
        $couchbase = new \Couchbase(
            $config['hosts'],
            $config['user'],
            $config['password'],
            $config['bucket'],
            $config['persistent']
        );

        $cache = new CouchbaseCache();

        $cache->setCouchbase($couchbase);

        return $cache;
    }
}