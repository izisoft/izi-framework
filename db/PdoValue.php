<?php
/**
 * @link https://framework.iziweb.net/
 * @copyright Copyright (c) 2021 Izi Software LLC
 * @license https://framework.iziweb.net/license/
 */

namespace izi\db;

/**
 * Class PdoValue represents a $value that should be bound to PDO with exact $type.
 *
 * For example, it will be useful when you need to bind binary data to BLOB column in DBMS:
 *
 * ```php
 * [':name' => 'John', ':profile' => new PdoValue($profile, \PDO::PARAM_LOB)]`.
 * ```
 *
 * To see possible types, check [PDO::PARAM_* constants](https://secure.php.net/manual/en/pdo.constants.php).
 *
 * @see https://secure.php.net/manual/en/pdostatement.bindparam.php
 * @author Giang A Tin <vantruong1898@gmail.com>
 * @since 2.0.14
 */
final class PdoValue implements ExpressionInterface
{
    /**
     * @var mixed
     */
    private $value;
    /**
     * @var int One of PDO_PARAM_* constants
     * @see https://secure.php.net/manual/en/pdo.constants.php
     */
    private $type;


    /**
     * PdoValue constructor.
     *
     * @param $value
     * @param $type
     */
    public function __construct($value, $type)
    {
        $this->value = $value;
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }
}