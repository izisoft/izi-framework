<?php
/**
 * @link https://framework.iziweb.net/
 * @copyright Copyright (c) 2021 Izi Software LLC
 * @license https://framework.iziweb.net/license/
 */

namespace izi\db\sqlite\conditions;

use izi\base\NotSupportedException;

/**
 * {@inheritdoc}
 *
 * @author Giang A Tin <vantruong1898@gmail.com>
 * @since 2.0.14
 */
class InConditionBuilder extends \izi\db\conditions\InConditionBuilder
{
    /**
     * {@inheritdoc}
     * @throws NotSupportedException if `$columns` is an array
     */
    protected function buildSubqueryInCondition($operator, $columns, $values, &$params)
    {
        if (is_array($columns)) {
            throw new NotSupportedException(__METHOD__ . ' is not supported by SQLite.');
        }

        return parent::buildSubqueryInCondition($operator, $columns, $values, $params);
    }

    /**
     * {@inheritdoc}
     */
    protected function buildCompositeInCondition($operator, $columns, $values, &$params)
    {
        $quotedColumns = [];
        foreach ($columns as $i => $column) {
            $quotedColumns[$i] = strpos($column, '(') === false ? $this->queryBuilder->db->quoteColumnName($column) : $column;
        }
        $vss = [];
        foreach ($values as $value) {
            $vs = [];
            foreach ($columns as $i => $column) {
                if (isset($value[$column])) {
                    $phName = $this->queryBuilder->bindParam($value[$column], $params);
                    $vs[] = $quotedColumns[$i] . ($operator === 'IN' ? ' = ' : ' != ') . $phName;
                } else {
                    $vs[] = $quotedColumns[$i] . ($operator === 'IN' ? ' IS' : ' IS NOT') . ' NULL';
                }
            }
            $vss[] = '(' . implode($operator === 'IN' ? ' AND ' : ' OR ', $vs) . ')';
        }

        return '(' . implode($operator === 'IN' ? ' OR ' : ' AND ', $vss) . ')';
    }
}
