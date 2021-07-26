<?php
/**
 * @link https://framework.iziweb.net/
 * @copyright Copyright (c) 2021 Izi Software LLC
 * @license https://framework.iziweb.net/license/
 */

namespace izi\db\oci;

/**
 * Command represents an Oracle SQL statement to be executed against a database.
 *
 * {@inheritdoc}
 *
 * @since 2.0.33
 */
class Command extends \izi\db\Command
{
    /**
     * {@inheritdoc}
     */
    protected function bindPendingParams()
    {
        $paramsPassedByReference = [];
        foreach ($this->pendingParams as $name => $value) {
            if (\PDO::PARAM_STR === $value[1]) {
                $paramsPassedByReference[$name] = $value[0];
                $this->pdoStatement->bindParam($name, $paramsPassedByReference[$name], $value[1], strlen($value[0]));
            } else {
                $this->pdoStatement->bindValue($name, $value[0], $value[1]);
            }
        }
        $this->pendingParams = [];
    }
}
