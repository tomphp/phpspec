<?php

/*
 * This file is part of PhpSpec, A php toolset to drive emergent
 * design by specification.
 *
 * (c) Marcello Duarte <marcello.duarte@gmail.com>
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhpSpec\Config;

use PhpSpec\Exception\Config\SuiteConfigException;
use PhpSpec\Exception\Config\ExtensionConfigException;

class Config
{
    const DEFAULT_FORMATTER_NAME = 'progress';

    /**
      * @var string[]
      */
    private static $validSuiteSettings = array(
        'namespace',
        'spec_prefix',
        'src_path',
        'spec_path',
        'psr4_prefix'
    );

    /**
      * @var string
      */
    private $homeDir;

    /**
      * @var string
      */
    private $currentWorkingDir;

    /**
      * @var array
      */
    private $suites;

    /**
      * @var string
      */
    private $formatterName;

    /**
      * @var array
      */
    private $extensions;

    /**
      * @var array
      */
    private $generatorTemplates;

    /**
      * @var int
      */
    private $runnerErrorLevels;

    /**
     * @param array $config
     *
     * @throws \PhpSpec\Exception\Config\SuiteConfigException
     */
    public function __construct(array $config, $homeDir, $currentWorkingDir)
    {
        $this->homeDir           = $homeDir;
        $this->currentWorkingDir = $currentWorkingDir;

        $this->suites             = $this->loadSuitesConfig($config);
        $this->formatterName      = $this->loadFormatterConfig($config);
        $this->extensions         = $this->loadExtensionsConfig($config);
        $this->generatorTemplates = $this->loadGeneratorTemplatesConfig($config);
        $this->runnerErrorLevels  = $this->loadRunnerErrorLevelConfig($config);
    }

    /**
     * @return array
     */
    public function getSuites()
    {
        return $this->suites;
    }

    /**
     * @return string
     */
    public function getFormatterName()
    {
        return $this->formatterName;
    }

    /**
     * @return array
     */
    public function getExtensions()
    {
        return $this->extensions;
    }

    /**
     * @return string[]
     */
    public function getCodeGeneratorTemplatePaths()
    {
        return $this->generatorTemplates;
    }

    /**
     * @return int
     */
    public function getRunnerErrorLevels()
    {
        return $this->runnerErrorLevels;
    }

    /**
     * @param array $config
     *
     * @return array
     */
    private function loadSuitesConfig(array $config)
    {
        return $this->loadCallbackIfKeyExists(
            $config,
            'suites',
            function ($config) {
                if (!is_array($config)) {
                    throw SuiteConfigException::suitesMustBeAList();
                }

                return $this->processSuitesConfig($config);
            },
            array('main' => '')
        );
    }

    /**
     * @param array $config
     *
     * @return string
     */
    private function loadFormatterConfig(array $config)
    {
        return $this->loadValueIfKeyExists(
            $config,
            'formatter.name',
            self::DEFAULT_FORMATTER_NAME
        );
    }

    /**
     * @param array $config
     *
     * @return array
     */
    private function loadExtensionsConfig(array $config)
    {
        return $this->loadCallbackIfKeyExists(
            $config,
            'extensions',
            function ($config) {
                if (!is_array($config)) {
                    throw ExtensionConfigException::mustBeAList();
                }

                return $config;
            },
            array()
        );
    }

    /**
     * @param array $config
     *
     * @return array
     */
    private function loadGeneratorTemplatesConfig(array $config)
    {
        return $this->loadCallbackIfKeyExists(
            $config,
            'code_generator.templates.paths',
            function ($config) {
                if (!is_array($config)) {
                    return array($config);
                }

                return $config;
            },
            array(
                $this->currentWorkingDir . DIRECTORY_SEPARATOR . '.phpspec',
                $this->homeDir . DIRECTORY_SEPARATOR . '.phpspec'
            )
        );
    }

    /**
     * @param array $config
     *
     * @return int
     */
    private function loadRunnerErrorLevelConfig(array $config)
    {
        return $this->loadValueIfKeyExists(
            $config,
            'runner.maintainers.errors.levels',
            E_ALL ^ E_STRICT
        );
    }

    /**
     * @param array  $config
     * @param string $configKey
     * @param mixed  $default
     *
     * @return mixed
     */
    private function loadValueIfKeyExists(array $config, $configKey, $default)
    {
        return $this->loadCallbackIfKeyExists(
            $config,
            $configKey,
            function ($config) {
                return $config;
            },
            $default
        );
    }

    /**
     * @param array    $config
     * @param string   $configKey
     * @param callable $loadAction
     * @param mixed    $default
     *
     * @return mixed
     */
    private function loadCallbackIfKeyExists(array $config, $configKey, callable $loadAction, $default)
    {
        if (!array_key_exists($configKey, $config)) {
            return $default;
        }

        return $loadAction($config[$configKey]);
    }

    /**
     * @param array $suiteConfig
     *
     * @return array
     */
    private function processSuitesConfig(array $suiteConfig)
    {
        $suites = array();

        foreach ($suiteConfig as $suite => $settings) {
            $suites[$suite] = $this->processSuiteConfig($suite, $settings);
        }

        return $suites;
    }

    /**
     * @param string $suite
     * @param mixed  $settings
     *
     * @return array
     */
    private function processSuiteConfig($suite, $settings)
    {
        if (!is_array($settings)) {
            return array('namespace' => $settings);
        }

        if (!array_key_exists('namespace', $settings)) {
            throw SuiteConfigException::suiteRequiresNamespace($suite);
        }

        foreach ($settings as $setting => $value) {
            $this->assertSuiteSettingIsValid($suite, $setting);
        }

        return $settings;
    }

    /**
     * @param string $suite
     * @param string $setting
     */
    private function assertSuiteSettingIsValid($suite, $setting)
    {
        if (!in_array($setting, self::$validSuiteSettings)) {
            throw SuiteConfigException::invalidSetting($suite, $setting);
        }
    }
}
