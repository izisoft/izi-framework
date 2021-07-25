<?php
/**
 * @link https://framework.iziweb.net
 * @copyright Copyright (c) 2021 Izi Software LLC
 * @license https://framework.iziweb.net/license
 */

namespace app\core\form;

use app\core\Model;

/**
 * Class InputField
 *
 * @package app\core\form
 * @author Giang A Tin <vantruong1898@gmail.com>
 * @since 1.0
 */
class InputField extends BaseField
{
    public const TYPE_TEXT = 'text';
    public const TYPE_PASSWORD= 'password';
    public const TYPE_EMAIL = 'email';
    public const TYPE_NUMBER = 'number';
    public string $type;

    /**
     * InputField constructor.
     * @param Model $model
     * @param string $attribute
     */
    public function __construct(Model $model, string  $attribute)
    {
        $this->type = self::TYPE_TEXT;
        parent::__construct($model, $attribute);
    }

    /**
     * @return $this
     */
    public function passwordField(): InputField
    {
        $this->type = self::TYPE_PASSWORD;
        return $this;
    }

    /**
     * @return $this
     */
    public function emailField(): InputField
    {
        $this->type = self::TYPE_EMAIL;
        return $this;
    }

    /**
     * @return $this
     */
    public function numberField(): InputField
    {
        $this->type = self::TYPE_NUMBER;
        return $this;
    }

    /**
     * Abstract function extend from BaseField
     * @return string
     */
    public function renderInput(): string
    {
        return sprintf('<input type="%s" name="%s" value="%s" class="form-control %s">',
            $this->type,
            $this->attribute,
            $this->model->{$this->attribute} ?? '',
            $this->model->hasError($this->attribute) ? 'is-invalid' : '',
        );
    }
}