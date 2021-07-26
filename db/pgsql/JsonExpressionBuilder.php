<?php
/**
 * @link https://framework.iziweb.net/
 * @copyright Copyright (c) 2021 Izi Software LLC
 * @license https://framework.iziweb.net/license/
 */

namespace izi\db\pgsql;

use izi\db\ArrayExpression;
use izi\db\ExpressionBuilderInterface;
use izi\db\ExpressionBuilderTrait;
use izi\db\ExpressionInterface;
use izi\db\JsonExpression;
use izi\db\Query;
use izi\helpers\Json;

/**
 * Class JsonExpressionBuilder builds [[JsonExpression]] for PostgreSQL DBMS.
 *
 * @author Giang A Tin <vantruong1898@gmail.com>
 * @since 2.0.14
 */
class JsonExpressionBuilder implements ExpressionBuilderInterface
{
    use ExpressionBuilderTrait;


    /**
     * {@inheritdoc}
     * @param JsonExpression|ExpressionInterface $expression the expression to be built
     */
    public function build(ExpressionInterface $expression, array &$params = [])
    {
        $value = $expression->getValue();

        if ($value instanceof Query) {
            list ($sql, $params) = $this->queryBuilder->build($value, $params);
            return "($sql)" . $this->getTypecast($expression);
        }
        if ($value instanceof ArrayExpression) {
            $placeholder = 'array_to_json(' . $this->queryBuilder->buildExpression($value, $params) . ')';
        } else {
            $placeholder = $this->queryBuilder->bindParam(Json::encode($value), $params);
        }

        return $placeholder . $this->getTypecast($expression);
    }

    /**
     * @param JsonExpression $expression
     * @return string the typecast expression based on [[type]].
     */
    protected function getTypecast(JsonExpression $expression)
    {
        if ($expression->getType() === null) {
            return '';
        }

        return '::' . $expression->getType();
    }
}
