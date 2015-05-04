<?php namespace Atrauzzi\LaravelDoctrine;

class CacheFactory {

    public static function getInstance($type, $namespace)
    {

        switch($type) {

            case 'memcache':

                $memcache = new \Memcache();
                $memcache->connect(
                    config('doctrine.cache.memcache.host'),
                    config('doctrine.cache.memcache.port')
                );

                $cache = new \Doctrine\Common\Cache\MemcacheCache();

                $cache->setMemcache($memcache);

                break;

            case 'memcached':

                $memcache = new \Memcached();
                $memcache->addServer(
                    config('doctrine.cache.memcached.host', config('cache.stores.memcached.0.host')),
                    config('doctrine.cache.memcached.port', config('cache.stores.memcached.0.port'))
                );

                $cache = new \Doctrine\Common\Cache\MemcachedCache();

                $cache->setMemcached($memcache);

                break;

            case 'couchbase':

                $couchbase = new \Couchbase(
                    config('doctrine.cache.couchbase.hosts'),
                    config('doctrine.cache.couchbase.user'),
                    config('doctrine.cache.couchbase.password'),
                    config('doctrine.cache.couchbase.bucket'),
                    config('doctrine.cache.couchbase.persistent')
                );

                $cache = new \Doctrine\Common\Cache\CouchbaseCache();

                $cache->setCouchbase($couchbase);

                break;

            case 'redis':

                $redis = new \Redis();
                $redis->connect(
                    config('doctrine.cache.redis.host', config('database.redis.default.host')),
                    config('doctrine.cache.redis.port', config('database.redis.default.port'))
                );

                if($database = config('doctrine.cache.redis.database', config('database.redis.default.database')))
                    $redis->select($database);

                $cache = new \Doctrine\Common\Cache\RedisCache();

                $cache->setRedis($redis);

                break;

            case 'apc':
                $cache = new \Doctrine\Common\Cache\ApcCache();
                break;

            case 'xcache':
                $cache = new \Doctrine\Common\Cache\XcacheCache();
                break;

            default:
                $cache = new \Doctrine\Common\Cache\ArrayCache();
                break;

        }

        if(
            $cache instanceof \Doctrine\Common\Cache\CacheProvider
            && $namespace = config('doctrine.cache.namespace', config('cache.prefix'))
        )
            $cache->setNamespace($namespace);

        return $cache;
    }

}