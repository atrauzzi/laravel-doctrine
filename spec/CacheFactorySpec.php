<?php

namespace spec\Atrauzzi\LaravelDoctrine;

use Atrauzzi\LaravelDoctrine\CacheFactory;
use PhpSpec\Exception\Example\ExampleException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Debug\Exception\ClassNotFoundException;

class CacheFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Atrauzzi\LaravelDoctrine\CacheFactory');
    }

    function it_should_throw_runtime_exception_for_unmapped_provider()
    {
        $this->beConstructedThrough('getCacheProvider',['unsupported',[]]);
        try{
            $this->getWrappedObject();
            throw new ExampleException('but no runtime exception was thrown.');
        }
        catch(\RuntimeException $e){}
    }

    function it_should_throw_classnotfound_exception_for_incorrectly_mapped_supported_provider()
    {
        \Atrauzzi\LaravelDoctrine\CacheFactory::setProviders(['memcached'=>'Bad\Path']);
        $this->beConstructedThrough('getCacheProvider',['memcached',[]]);
        try{
            $this->getWrappedObject();
            throw new ExampleException('but no runtime exception was thrown.');
        }
        catch(ClassNotFoundException $e){}
    }

    function it_should_throw_invalid_argument_exception()
    {
        \Atrauzzi\LaravelDoctrine\CacheFactory::setProviders(['memcached'=>'Atrauzzi\LaravelDoctrine\CacheProvider\MemcachedProvider']);
        $this->beConstructedThrough('getCacheProvider',['memcached',[]]);
        try{
            $this->getWrappedObject();
            throw new ExampleException('but no runtime exception was thrown.');
        }
        catch(\InvalidArgumentException $e){}
    }

    function it_should_return_memcached_provider()
    {
        \Atrauzzi\LaravelDoctrine\CacheFactory::setProviders(['memcached'=>'Atrauzzi\LaravelDoctrine\CacheProvider\MemcachedProvider']);
        $this->beConstructedThrough('getCacheProvider',['memcached',['host'=>'127.0.0.1','port'=>'11211']]);
        $this->shouldHaveType('Doctrine\Common\Cache\MemcachedCache');
    }

}
