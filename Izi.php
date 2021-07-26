<?php
/**
 * @link https://framework.iziweb.net
 * @copyright Copyright (c) 2021 Izi Software LLC
 * @license https://framework.iziweb.net/license
 */
require_once __DIR__  . '/Base.php';
/**
 * Class Izi
 *
 * @package izi
 * @author Giang A Tin <vantruong1898@gmail.com>
 * @since 1.0
 */
class Izi extends \izi\Base
{

}
spl_autoload_register(['Izi', 'autoload'], true, true);
Izi::$classMap = require __DIR__ . '/classes.php';
Izi::$container = new \izi\di\Container();