<?php
/**
 * @link https://framework.iziweb.net
 * @copyright Copyright (c) 2021 Izi Software LLC
 * @license https://framework.iziweb.net/license
 */

namespace izi\forms;

use izi\base\Model;

/**
 * Class BaseField
 *
 * @package izi\forms
 * @author Giang A Tin <vantruong1898@gmail.com>
 * @since 1.0
 */
abstract class BaseField
{
    public Model $model;
    public string $attribute;
    protected string $label;
    /**
     * BaseField constructor.
     * @param Model $model
     * @param string $attribute
     */
    public function __construct(Model $model, string $attribute)
    {
        $this->model = $model;
        $this->attribute = $attribute;
        $this->label = $this->model->getLabel($attribute);
    }

    abstract public function renderInput(): string;

    public function __toString()
    {
        return sprintf('            
            <div class="form-group mb-3">
                <label class="form-label">%s</label>
                %s
                <div class="invalid-feedback">%s</div>
            </div>
        ',
            $this->label,
            $this->renderInput(),
            $this->model->getFirstError($this->attribute)
        );
    }

    /**
     * @param string $label
     * @return $this
     */
    public function label(string $label): BaseField
    {
        $this->label = $label;
        return $this;
    }

}