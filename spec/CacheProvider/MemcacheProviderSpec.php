<?php namespace spec\Atrauzzi\LaravelDoctrine\CacheProvider;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MemcacheProviderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Atrauzzi\LaravelDoctrine\CacheProvider\MemcacheProvider');
    }

    function it_should_initilize_and_return_cacheprovider()
    {
        $config = [['host' => '127.0.0.1', 'port' => '11211']];
        $this->beConstructedThrough('getCacheProvider', $config);
        $this->shouldHaveType('\Doctrine\Common\Cache\CacheProvider');
        $this->shouldHaveType('Doctrine\Common\Cache\MemcacheCache');
    }
}
