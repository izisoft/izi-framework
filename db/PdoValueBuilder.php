<?php
/**
 * @link https://framework.iziweb.net/
 * @copyright Copyright (c) 2021 Izi Software LLC
 * @license https://framework.iziweb.net/license/
 */

namespace izi\db;

/**
 * Class PdoValueBuilder builds object of the [[PdoValue]] expression class.
 *
 * @author Giang A Tin <vantruong1898@gmail.com>
 * @since 2.0.14
 */
class PdoValueBuilder implements ExpressionBuilderInterface
{
    const PARAM_PREFIX = ':pv';


    /**
     * {@inheritdoc}
     */
    public function build(ExpressionInterface $expression, array &$params = [])
    {
        $placeholder = static::PARAM_PREFIX . count($params);
        $params[$placeholder] = $expression;

        return $placeholder;
    }
}
