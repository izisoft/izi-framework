<?php
/**
 * @link https://framework.iziweb.net/
 * @copyright Copyright (c) 2021 Izi Software LLC
 * @license https://framework.iziweb.net/license/
 */

namespace izi\db\mssql;

/**
 * TableSchema represents the metadata of a database table.
 *
 * @author Giang A Tin <vantruong1898@gmail.com>
 * @since 2.0
 */
class TableSchema extends \izi\db\TableSchema
{
    /**
     * @var string name of the catalog (database) that this table belongs to.
     * Defaults to null, meaning no catalog (or the current database).
     */
    public $catalogName;
}
