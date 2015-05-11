<?php  namespace Atrauzzi\LaravelDoctrine\CacheProvider;

use Doctrine\Common\Cache\ApcCache;

/**
 * Class MemcachedProvider
 * @package Atrauzzi\LaravelDoctrine\CacheProvider
 */
class ApcCacheProvider extends CacheProvider{

    /**
     * Sets up and returns the CacheProvider
     * @param $config
     * @return \Doctrine\Common\Cache\CacheProvider
     */
    protected function initialize($config)
    {
        return new ApcCache();
    }
}