<?php
/**
 * @link https://framework.iziweb.net
 * @copyright Copyright (c) 2021 Izi Software LLC
 * @license https://framework.iziweb.net/license
 */

namespace app\core\form;

use app\core\Model;

/**
 * Class Form
 *
 * @package app\core\form
 * @author Giang A Tin <vantruong1898@gmail.com>
 * @since 1.0
 */
class Form
{
    /**
     * @param $action
     * @param $method
     * @return Form
     */
    public static function begin($action, $method): Form
    {
        echo sprintf('<form action="%s" method="%s">', $action, $method);
        return new Form();
    }

    /**
     * @return string
     */
    public static function end()
    {
        echo '</form>';
    }

    /**
     * @param Model $model
     * @param string $attribute
     * @return InputField
     */
    public function field(Model $model, string $attribute): InputField
    {
        return new InputField($model, $attribute);
    }

    public function textarea(Model $model, string $attribute): TextareaField
    {
        return new TextareaField($model, $attribute);
    }
}