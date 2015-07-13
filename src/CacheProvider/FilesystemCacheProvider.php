<?php

namespace Atrauzzi\LaravelDoctrine\CacheProvider;

use Doctrine\Common\Cache\FilesystemCache;

/**
 * Class FilesystemCacheProvider
 * @package Atrauzzi\LaravelDoctrine\CacheProvider
 */
class FilesystemCacheProvider extends CacheProvider {

    /** {@inheritdoc} */
    protected function initialize($config) {
        return new FilesystemCache($config['directory'], $config['extension']);
    }

}
