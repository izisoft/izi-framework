<?php
/**
 * @link https://framework.iziweb.net
 * @copyright Copyright (c) 2021 Izi Software LLC
 * @license https://framework.iziweb.net/license
 */

namespace app\core\form;

/**
 * Class TextareaField
 *
 * @package app\core\form
 * @author Giang A Tin <vantruong1898@gmail.com>
 * @since 1.0
 */
class TextareaField extends BaseField
{

    public function renderInput(): string
    {
        return sprintf('<textarea name="%s" class="form-control %s">%s</textarea>',
            $this->attribute,
            $this->model->hasError($this->attribute) ? 'is-invalid' : '',
            $this->model->{$this->attribute} ?? '',
        );
    }
}