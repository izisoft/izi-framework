<?php
/**
 * @link https://framework.iziweb.net
 * @copyright Copyright (c) 2021 Izi Software LLC
 * @license https://framework.iziweb.net/license
 */

namespace izi\base;

/**
 * Class NotSupportedException
 *
 * @package izi\base
 * @author Giang A Tin <vantruong1898@gmail.com>
 * @since 1.0
 */
class NotSupportedException extends Exception
{
    public function getName()
    {
        return 'Not Supported';
    }
}