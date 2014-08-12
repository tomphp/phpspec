<?php

namespace spec\PhpSpec\Config;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use PhpSpec\Exception\Config\SuiteConfigException;
use PhpSpec\Exception\Config\ExtensionConfigException;

class ConfigSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(array());
    }

    function it_defaults_to_return_an_empty_list_of_suites()
    {
        $this->getSuites()->shouldReturn(array());
    }

    function it_throws_if_suites_config_is_not_a_list()
    {
        $this->shouldThrow(new SuiteConfigException(
            'Config section for suites must be a list.'
        ))->during('__construct', array(array('suites' => 'not an array')));
    }

    function it_sets_namespace_value_if_suite_config_is_not_a_list()
    {
        $this->beConstructedWith(
            array('suites' => array('nolist' => 'not_an_array'))
        );

        $this->getSuites()->shouldReturn(array(
            'nolist' => array(
                'namespace' => 'not_an_array'
            )
        ));
    }

    function it_throws_if_suite_config_does_not_contain_a_namespace()
    {
        $this->shouldThrow(new SuiteConfigException(
            'Config for suite "nonamespace" must contain a "namespace" setting.'
        ))->during('__construct', array(
            array('suites' => array('nonamespace' => array()))
        ));
    }

    function it_throws_if_suite_config_contains_invalid_settings()
    {
        $this->shouldThrow(new SuiteConfigException(
            'Config for suite "badsetting" contains invalid setting "invalid".'
        ))->during('__construct', array(
            array('suites' => array(
                'badsetting' => array('namespace' => 'ns', 'invalid' => true)
            ))
        ));
    }

    function it_returns_provides_the_suites_settings_from_the_config()
    {
        $this->beConstructedWith(array(
            'suites' => array(
                'testsuite' => array(
                    'namespace'   => 'TestNamespace',
                    'spec_prefix' => 'SpecPrefix',
                    'src_path'    => 'src/path',
                    'spec_path'   => 'spec/path'
                )
            )
        ));

        $this->getSuites()->shouldReturn(array(
            'testsuite' => array(
                'namespace'   => 'TestNamespace',
                'spec_prefix' => 'SpecPrefix',
                'src_path'    => 'src/path',
                'spec_path'   => 'spec/path'
            )
        ));
    }

    function it_defaults_to_use_the_progress_formatter()
    {
        $this->getFormatterName()->shouldReturn('progress');
    }

    function it_returns_the_formatter_name_from_the_config()
    {
        $this->beConstructedWith(array(
            'formatter.name' => 'pretty'
        ));

        $this->getFormatterName()->shouldReturn('pretty');
    }

    function it_defaults_to_return_an_empty_list_of_extensions()
    {
        $this->getExtensions()->shouldReturn(array());
    }

    function it_throws_if_extensions_config_is_not_a_list()
    {
        $this->shouldThrow(new ExtensionConfigException(
            'Config section for extensions must be a list.'
        ))->during('__construct', array(array('extensions' => 'not an array')));
    }
}
