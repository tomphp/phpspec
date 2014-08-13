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
        $this->beConstructedWith(array(), '/home/user', '/cwd');
    }

    function it_defaults_to_return_an_empty_list_of_suites()
    {
        $this->getSuites()->shouldReturn(array('main' => ''));
    }

    function it_throws_if_suites_config_is_not_a_list()
    {
        $this->shouldThrow(new SuiteConfigException(
            'Config section for suites must be a list.'
        ))->during('__construct', array(
            array('suites' => 'not an array'),
            '/home/dir',
            '/cwd'
        ));
    }

    function it_sets_namespace_value_if_suite_config_is_not_a_list()
    {
        $this->beConstructedWith(
            array('suites' => array('nolist' => 'not_an_array')),
            '/home/user',
            '/cwd'
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
            array('suites' => array('nonamespace' => array())),
            '/home/user',
            '/cwd'
        ));
    }

    function it_throws_if_suite_config_contains_invalid_settings()
    {
        $this->shouldThrow(new SuiteConfigException(
            'Config for suite "badsetting" contains invalid setting "invalid".'
        ))->during('__construct', array(
            array('suites' => array(
                'badsetting' => array('namespace' => 'ns', 'invalid' => true)
            )),
            '/home/user',
            '/cwd'
        ));
    }

    function it_returns_provides_the_suites_settings_from_the_config()
    {
        $this->beConstructedWith(
            array(
                'suites' => array(
                    'testsuite' => array(
                        'namespace'   => 'TestNamespace',
                        'spec_prefix' => 'SpecPrefix',
                        'src_path'    => 'src/path',
                        'spec_path'   => 'spec/path',
                        'psr4_prefix' => 'PSR4Prefix'
                    )
                )
            ),
            '/home/user',
            '/cwd'
        );

        $this->getSuites()->shouldReturn(array(
            'testsuite' => array(
                'namespace'   => 'TestNamespace',
                'spec_prefix' => 'SpecPrefix',
                'src_path'    => 'src/path',
                'spec_path'   => 'spec/path',
                'psr4_prefix' => 'PSR4Prefix'
            )
        ));
    }

    function it_defaults_to_use_the_progress_formatter()
    {
        $this->getFormatterName()->shouldReturn('progress');
    }

    function it_returns_the_formatter_name_from_the_config()
    {
        $this->beConstructedWith(
            array('formatter.name' => 'pretty'),
            '/home/user',
            '/cwd'
        );

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
        ))->during('__construct', array(
            array('extensions' => 'not an array'),
            '/home/user',
            '/cwd'
        ));
    }

    function it_returns_extensions_from_the_config()
    {
        $this->beConstructedWith(
            array('extensions' => array('Extension1', 'Extension2')),
            '/home/user',
            '/cwd'
        );

        $this->getExtensions()->shouldReturn(array('Extension1', 'Extension2'));
    }

    function it_returns_default_code_generation_template_paths()
    {
        $this->beConstructedWith(array(), '/home/user', '/cwd');

        $this->getCodeGeneratorTemplatePaths()->shouldReturn(array(
            '/cwd' . DIRECTORY_SEPARATOR . '.phpspec',
            '/home/user' . DIRECTORY_SEPARATOR . '.phpspec'
        ));
    }

    function it_returns_single_code_gen_template_path_if_list_is_not_provided()
    {
        $this->beConstructedWith(
            array('code_generator.templates.paths' => '/template/path'),
            '/home/user',
            '/cwd'
        );

        $this->getCodeGeneratorTemplatePaths()->shouldReturn(array(
            '/template/path'
        ));
    }

    function it_returns_all_code_gen_template_paths_specifed()
    {
        $this->beConstructedWith(
            array('code_generator.templates.paths' => array(
                '/template/path1',
                '/template/path2'
            )),
            '/home/user',
            '/cwd'
        );

        $this->getCodeGeneratorTemplatePaths()->shouldReturn(array(
            '/template/path1',
            '/template/path2'
        ));
    }

    function it_returns_default_runner_error_levels()
    {
        $this->getRunnerErrorLevels()->shouldReturn(E_ALL ^ E_STRICT);
    }

    function it_returns_runners_errors_from_config()
    {
        $this->beConstructedWith(
            array('runner.maintainers.errors.levels' => E_ALL),
            '/home/user',
            '/cwd'
        );

        $this->getRunnerErrorLevels()->shouldReturn(E_ALL);
    }
}
