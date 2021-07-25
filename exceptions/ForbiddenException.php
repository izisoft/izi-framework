<?php
/**
 * @link https://framework.iziweb.net
 * @copyright Copyright (c) 2021 Izi Software LLC
 * @license https://framework.iziweb.net/license
 */

namespace izi\exceptions;

/**
 * Class ForbiddenException
 *
 * @package izi\exceptions
 * @author Giang A Tin <vantruong1898@gmail.com>
 * @since 1.0
 */
class ForbiddenException extends \Exception
{
    protected $code = 403;
    protected $message = 'You don\'t permission to access this page';
}