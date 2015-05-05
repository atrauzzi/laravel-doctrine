<?php

namespace spec\Atrauzzi\LaravelDoctrine;

use Atrauzzi\LaravelDoctrine\CacheFactory;
use PhpSpec\Exception\Example\ExampleException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CacheFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Atrauzzi\LaravelDoctrine\CacheFactory');
    }

    function it_should_throw_runtime_exception_for_unmapped_provider()
    {

    }

    function it_should_throw_classnotfound_exception_for_incorrectly_mapped_supported_provider()
    {

    }

}
