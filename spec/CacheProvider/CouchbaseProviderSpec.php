<?php

namespace spec\Atrauzzi\LaravelDoctrine\CacheProvider;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CouchbaseProviderSpec extends ObjectBehavior
{
    /*
     *  Not messing with getting couch base installed to test this
     *
    function it_is_initializable()
    {
        $this->shouldHaveType('Atrauzzi\LaravelDoctrine\CacheProvider\CouchbaseProvider');
    }

    function it_should_initilize_and_return_cacheprovider()
    {
        $config = [null];
        $this->beConstructedThrough('getCacheProvider', $config);
        $this->shouldHaveType('\Doctrine\Common\Cache\CacheProvider');
        $this->shouldHaveType('Doctrine\Common\Cache\CouchbaseCache');
    }
    */

}
