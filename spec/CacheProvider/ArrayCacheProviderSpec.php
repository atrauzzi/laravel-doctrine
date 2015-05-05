<?php

namespace spec\Atrauzzi\LaravelDoctrine\CacheProvider;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ArrayCacheProviderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Atrauzzi\LaravelDoctrine\CacheProvider\ArrayCacheProvider');
    }

    function it_should_initilize_and_return_cacheprovider()
    {
        $config = [null];
        $this->beConstructedThrough('getCacheProvider', $config);
        $this->shouldHaveType('\Doctrine\Common\Cache\CacheProvider');
        $this->shouldHaveType('Doctrine\Common\Cache\ArrayCache');
    }

}
