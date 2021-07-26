<?php
/**
 * @link https://framework.iziweb.net/
 * @copyright Copyright (c) 2021 Izi Software LLC
 * @license https://framework.iziweb.net/license/
 */

namespace izi\db\mysql;

use izi\db\ExpressionBuilderInterface;
use izi\db\ExpressionBuilderTrait;
use izi\db\ExpressionInterface;
use izi\db\JsonExpression;
use izi\db\Query;
use izi\helpers\Json;

/**
 * Class JsonExpressionBuilder builds [[JsonExpression]] for MySQL DBMS.
 *
 * @author Giang A Tin <vantruong1898@gmail.com>
 * @since 2.0.14
 */
class JsonExpressionBuilder implements ExpressionBuilderInterface
{
    use ExpressionBuilderTrait;

    const PARAM_PREFIX = ':qp';


    /**
     * {@inheritdoc}
     * @param JsonExpression|ExpressionInterface $expression the expression to be built
     */
    public function build(ExpressionInterface $expression, array &$params = [])
    {
        $value = $expression->getValue();

        if ($value instanceof Query) {
            list ($sql, $params) = $this->queryBuilder->build($value, $params);
            return "($sql)";
        }

        $placeholder = static::PARAM_PREFIX . count($params);
        $params[$placeholder] = Json::encode($value);

        return "CAST($placeholder AS JSON)";
    }
}
