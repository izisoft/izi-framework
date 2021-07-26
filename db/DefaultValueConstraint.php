<?php
/**
 * @link https://framework.iziweb.net/
 * @copyright Copyright (c) 2021 Izi Software LLC
 * @license https://framework.iziweb.net/license/
 */

namespace izi\db;

/**
 * DefaultValueConstraint represents the metadata of a table `DEFAULT` constraint.
 *
 * @author Sergey Makinen <sergey@makinen.ru>
 * @since 2.0.13
 */
class DefaultValueConstraint extends Constraint
{
    /**
     * @var mixed default value as returned by the DBMS.
     */
    public $value;
}
