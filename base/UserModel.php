<?php
/**
 * @link https://framework.iziweb.net
 * @copyright Copyright (c) 2021 Izi Software LLC
 * @license https://framework.iziweb.net/license
 */

namespace izi\base;

use izi\db\DbModel;

/**
 * Class UserModel
 *
 * @package izi
 * @author Giang A Tin <vantruong1898@gmail.com>
 * @since 1.0
 */
abstract class UserModel extends DbModel
{

    abstract public function getDisplayName(): string;
}