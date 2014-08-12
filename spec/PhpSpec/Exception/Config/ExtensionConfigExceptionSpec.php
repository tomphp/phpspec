<?php

namespace spec\PhpSpec\Exception\Config;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ExtensionConfigExceptionSpec extends ObjectBehavior
{
    function it_is_a_phpspec_exception()
    {
        $this->shouldBeAnInstanceOf('PhpSpec\Exception\Exception');
    }
}
