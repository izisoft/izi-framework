<?php
/**
 * @link https://framework.iziweb.net/
 * @copyright Copyright (c) 2021 Izi Software LLC
 * @license https://framework.iziweb.net/license/
 */

namespace izi\db\conditions;

use izi\base\InvalidArgumentException;
use izi\db\Query;

/**
 * Condition that represents `EXISTS` operator.
 *
 * @author Giang A Tin <vantruong1898@gmail.com>
 * @since 2.0.14
 */
class ExistsCondition implements ConditionInterface
{
    /**
     * @var string $operator the operator to use (e.g. `EXISTS` or `NOT EXISTS`)
     */
    private $operator;
    /**
     * @var Query the [[Query]] object representing the sub-query.
     */
    private $query;


    /**
     * ExistsCondition constructor.
     *
     * @param string $operator the operator to use (e.g. `EXISTS` or `NOT EXISTS`)
     * @param Query $query the [[Query]] object representing the sub-query.
     */
    public function __construct($operator, $query)
    {
        $this->operator = $operator;
        $this->query = $query;
    }

    /**
     * {@inheritdoc}
     */
    public static function fromArrayDefinition($operator, $operands)
    {
        if (!isset($operands[0]) || !$operands[0] instanceof Query) {
            throw new InvalidArgumentException('Subquery for EXISTS operator must be a Query object.');
        }

        return new static($operator, $operands[0]);
    }

    /**
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @return Query
     */
    public function getQuery()
    {
        return $this->query;
    }
}
