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
    );

    /**
      * @var array
      */
    private $suites;

    /**
      * @var string
      */
    private $formatterName;

    /**
     * @param array $config
     *
     * @throws \PhpSpec\Exception\Config\SuiteConfigException
     */
    public function __construct(array $config)
    {
        $this->suites        = $this->loadSuitesConfig($config);
        $this->formatterName = $this->loadFormatterConfig($config);
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
        return array();
    }

    /**
     * @param array $config
     *
     * @return array
     */
    private function loadSuitesConfig(array $config)
    {
        if (!array_key_exists('suites', $config)) {
            return array();
        }

        if (!is_array($config['suites'])) {
            throw SuiteConfigException::suitesMustBeAList();
        }

        return $this->processSuitesConfig($config['suites']);
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

    /**
     * @param array $config
     *
     * @return string
     */
    private function loadFormatterConfig(array $config)
    {
        if (!array_key_exists('formatter.name', $config)) {
            return self::DEFAULT_FORMATTER_NAME;
        }

        return $config['formatter.name'];
    }
}
