<?php
/**
 * @link https://framework.iziweb.net
 * @copyright Copyright (c) 2021 Izi Software LLC
 * @license https://framework.iziweb.net/license
 */

namespace app\core;

/**
 * Class Response
 *
 * @package app\core
 * @author Giang A Tin <vantruong1898@gmail.com>
 * @since 1.0
 */
class Response
{
    public function setStatusCode(int $code)
    {
        http_response_code($code);
    }

    public function redirect(string $url, int $code = 302)
    {
        header("Location: $url", true, $code);
    }
}