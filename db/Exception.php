<?php
/**
 * @link https://framework.iziweb.net/
 * @copyright Copyright (c) 2021 Izi Software LLC
 * @license https://framework.iziweb.net/license/
 */

namespace izi\db;

/**
 * Exception represents an exception that is caused by some DB-related operations.
 *
 * @author Giang A Tin <vantruong1898@gmail.com>
 * @since 2.0
 */
class Exception extends \izi\base\Exception
{
    /**
     * @var array the error info provided by a PDO exception. This is the same as returned
     * by [PDO::errorInfo](https://secure.php.net/manual/en/pdo.errorinfo.php).
     */
    public $errorInfo = [];


    /**
     * Constructor.
     * @param string $message PDO error message
     * @param array $errorInfo PDO error info
     * @param string $code PDO error code
     * @param \Throwable|\Exception $previous The previous exception used for the exception chaining.
     */
    public function __construct($message, $errorInfo = [], $code = '', $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->errorInfo = $errorInfo;
        $this->code = $code;
    }

    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Database Exception';
    }

    /**
     * @return string readable representation of exception
     */
    public function __toString()
    {
        return parent::__toString() . PHP_EOL
        . 'Additional Information:' . PHP_EOL . print_r($this->errorInfo, true);
    }
}
