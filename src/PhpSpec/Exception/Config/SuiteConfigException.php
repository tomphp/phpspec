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

namespace PhpSpec\Exception\Config;

use PhpSpec\Exception\Exception;

class SuiteConfigException extends Exception
{
    /**
     * @return \PhpSpec\Exception\Config\SuiteConfigException
     */
    public static function suitesMustBeAList()
    {
        return new self('Config section for suites must be a list.');
    }

    /**
     * @param string $suite
     *
     * @return \PhpSpec\Exception\Config\SuiteConfigException
     */
    public static function suiteRequiresNamespace($suite)
    {
        return new self(sprintf('Config for suite "%s" must contain a "namespace" setting.', $suite));
    }

    /**
     * @param string $suite
     * @param string $setting
     *
     * @return \PhpSpec\Exception\Config\SuiteConfigException
     */
    public static function invalidSetting($suite, $setting)
    {
        return new self(sprintf(
            'Config for suite "%s" contains invalid setting "%s".',
            $suite,
            $setting
        ));
    }
}
