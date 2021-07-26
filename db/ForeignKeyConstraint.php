<?php
/**
 * @link https://framework.iziweb.net/
 * @copyright Copyright (c) 2021 Izi Software LLC
 * @license https://framework.iziweb.net/license/
 */

namespace izi\db;

/**
 * ForeignKeyConstraint represents the metadata of a table `FOREIGN KEY` constraint.
 *
 * @author Sergey Makinen <sergey@makinen.ru>
 * @since 2.0.13
 */
class ForeignKeyConstraint extends Constraint
{
    /**
     * @var string|null referenced table schema name.
     */
    public $foreignSchemaName;
    /**
     * @var string referenced table name.
     */
    public $foreignTableName;
    /**
     * @var string[] list of referenced table column names.
     */
    public $foreignColumnNames;
    /**
     * @var string|null referential action if rows in a referenced table are to be updated.
     */
    public $onUpdate;
    /**
     * @var string|null referential action if rows in a referenced table are to be deleted.
     */
    public $onDelete;
}
