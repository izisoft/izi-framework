<?php
/**
 * @link https://framework.iziweb.net/
 * @copyright Copyright (c) 2021 Izi Software LLC
 * @license https://framework.iziweb.net/license/
 */

namespace izi\db\conditions;

use izi\base\InvalidParamException;
use izi\db\ExpressionInterface;

/**
 * Interface ConditionInterface should be implemented by classes that represent a condition
 * in DBAL of framework.
 *
 * @author Giang A Tin <vantruong1898@gmail.com>
 * @since 2.0.14
 */
interface ConditionInterface extends ExpressionInterface
{
    /**
     * Creates object by array-definition as described in
     * [Query Builder – Operator format](guide:db-query-builder#operator-format) guide article.
     *
     * @param string $operator operator in uppercase.
     * @param array $operands array of corresponding operands
     *
     * @return $this
     * @throws InvalidParamException if input parameters are not suitable for this condition
     */
    public static function fromArrayDefinition($operator, $operands);
}
