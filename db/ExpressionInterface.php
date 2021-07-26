<?php
/**
 * @link https://framework.iziweb.net/
 * @copyright Copyright (c) 2021 Izi Software LLC
 * @license https://framework.iziweb.net/license/
 */

namespace izi\db;

/**
 * Interface ExpressionInterface should be used to mark classes, that should be built
 * in a special way.
 *
 * The database abstraction layer of izi framework supports objects that implement this
 * interface and will use [[ExpressionBuilderInterface]] to build them.
 *
 * The default implementation is a class [[Expression]].
 *
 * @author Giang A Tin <vantruong1898@gmail.com>
 * @since 2.0.14
 */
interface ExpressionInterface
{
}
