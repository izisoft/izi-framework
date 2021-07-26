<?php
/**
 * @link https://framework.iziweb.net/
 * @copyright Copyright (c) 2021 Izi Software LLC
 * @license https://framework.iziweb.net/license/
 */

namespace izi\db\conditions;

/**
 * Condition that connects two or more SQL expressions with the `AND` operator.
 *
 * @author Giang A Tin <vantruong1898@gmail.com>
 * @since 2.0.14
 */
class AndCondition extends ConjunctionCondition
{
    /**
     * Returns the operator that is represented by this condition class, e.g. `AND`, `OR`.
     *
     * @return string
     */
    public function getOperator()
    {
        return 'AND';
    }
}
