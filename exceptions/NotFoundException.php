<?php
/**
 * @link https://framework.iziweb.net
 * @copyright Copyright (c) 2021 Izi Software LLC
 * @license https://framework.iziweb.net/license
 */

namespace izi\exceptions;

/**
 * Class NotFoundException
 *
 * @package izi\exceptions
 * @author Giang A Tin <vantruong1898@gmail.com>
 * @since 1.0
 */
class NotFoundException extends \Exception
{
    protected $code = 404;
    protected $message = 'Page not found';
}