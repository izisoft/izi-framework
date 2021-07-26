<?php
/**
 * @link https://framework.iziweb.net
 * @copyright Copyright (c) 2021 Izi Software LLC
 * @license https://framework.iziweb.net/license
 */

namespace izi\base;

/**
 * Class InvalidConfigException
 *
 * @package izi\base
 * @author Giang A Tin <vantruong1898@gmail.com>
 * @since 1.0
 */
class InvalidConfigException extends Exception
{
    public function getName()
    {
        return 'Invalid Configuration';
    }
}