<?php
/**
 * @link https://framework.iziweb.net/
 * @copyright Copyright (c) 2021 Izi Software LLC
 * @license https://framework.iziweb.net/license/
 */

namespace izi\db\conditions;

use izi\base\InvalidArgumentException;

/**
 * Condition that inverts passed [[condition]].
 *
 * @author Giang A Tin <vantruong1898@gmail.com>
 * @since 2.0.14
 */
class NotCondition implements ConditionInterface
{
    /**
     * @var mixed the condition to be negated
     */
    private $condition;


    /**
     * NotCondition constructor.
     *
     * @param mixed $condition the condition to be negated
     */
    public function __construct($condition)
    {
        $this->condition = $condition;
    }

    /**
     * @return mixed
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * {@inheritdoc}
     * @throws InvalidArgumentException if wrong number of operands have been given.
     */
    public static function fromArrayDefinition($operator, $operands)
    {
        if (count($operands) !== 1) {
            throw new InvalidArgumentException("Operator '$operator' requires exactly one operand.");
        }

        return new static(array_shift($operands));
    }
}
