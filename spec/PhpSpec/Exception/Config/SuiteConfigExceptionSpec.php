<?php

namespace spec\PhpSpec\Exception\Config;

use PhpSpec\ObjectBehavior;

class SuiteConfigExceptionSpec extends ObjectBehavior
{
    function it_is_a_phpspec_exception()
    {
        $this->shouldBeAnInstanceOf('PhpSpec\Exception\Exception');
    }
}
