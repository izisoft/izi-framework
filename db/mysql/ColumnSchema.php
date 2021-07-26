<?php
/**
 * @link https://framework.iziweb.net/
 * @copyright Copyright (c) 2021 Izi Software LLC
 * @license https://framework.iziweb.net/license/
 */

namespace izi\db\mysql;

use izi\db\ExpressionInterface;
use izi\db\JsonExpression;

/**
 * Class ColumnSchema for MySQL database
 *
 * @author Giang A Tin <vantruong1898@gmail.com>
 * @since 2.0.14.1
 */
class ColumnSchema extends \izi\db\ColumnSchema
{
    /**
     * @var bool whether the column schema should OMIT using JSON support feature.
     * You can use this property to make upgrade to izi framework 2.0 easier.
     * Default to `false`, meaning JSON support is enabled.
     *
     * @since 2.0.14.1
     * @deprecated Since 2.0.14.1 and will be removed in 2.1.
     */
    public $disableJsonSupport = false;


    /**
     * {@inheritdoc}
     */
    public function dbTypecast($value)
    {
        if ($value === null) {
            return $value;
        }

        if ($value instanceof ExpressionInterface) {
            return $value;
        }

        if (!$this->disableJsonSupport && $this->dbType === Schema::TYPE_JSON) {
            return new JsonExpression($value, $this->type);
        }

        return $this->typecast($value);
    }

    /**
     * {@inheritdoc}
     */
    public function phpTypecast($value)
    {
        if ($value === null) {
            return null;
        }

        if (!$this->disableJsonSupport && $this->type === Schema::TYPE_JSON) {
            return json_decode($value, true);
        }

        return parent::phpTypecast($value);
    }
}
