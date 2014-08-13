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

class ExtensionConfigException extends Exception
{
    /**
     * @return \PhpSpec\Exception\Config\ExtensionConfigException
     */
    public static function mustBeAList()
    {
        return new self('Config section for extensions must be a list.');
    }
}
