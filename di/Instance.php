<?php
/**
 * @link https://framework.iziweb.net
 * @copyright Copyright (c) 2021 Izi Software LLC
 * @license https://framework.iziweb.net/license
 */

namespace izi\di;

use Exception;
use Izi;
use izi\base\InvalidConfigException;

/**
 * Class Instance
 *
 * @package izi\di
 * @author Giang A Tin <vantruong1898@gmail.com>
 * @since 1.0
 */
class Instance
{
    /**
     * @var string the component ID, class name, interface name or alias name
     */
    public $id;
    /**
     * @var bool if null should be returned instead of throwing an exception
     */
    public $optional;

    /**
     * Instance constructor.
     * @param string $id
     * @param bool $optional
     */
    public function __construct(string $id, bool $optional = false)
    {
        $this->id = $id;
        $this->optional = $optional;
    }

    public static function of($id, $optional = false)
    {
        return new static($id, $optional);
    }

    public static function ensure($reference, $type = null, $container = null)
    {
        if (is_array($reference)) {
            $class = isset($reference['class']) ? $reference['class'] : $type;
            if (!$container instanceof Container) {
                $container = Izi::$container;
            }
            unset($reference['class']);
            $component = $container->get($class, [], $reference);
            if ($type === null || $component instanceof $type) {
                return $component;
            }

            throw new InvalidConfigException('Invalid data type: ' . $class . '. ' . $type . ' is expected.');
        } elseif (empty($reference)) {
            throw new InvalidConfigException('The required component is not specified.');
        }

        if (is_string($reference)) {
            $reference = new static($reference);
        } elseif ($type === null || $reference instanceof $type) {
            return $reference;
        }

        if ($reference instanceof self) {
            try {
                $component = $reference->get($container);
            } catch (\ReflectionException $e) {
                throw new InvalidConfigException('Failed to instantiate component or class "' . $reference->id . '".', 0, $e);
            }
            if ($type === null || $component instanceof $type) {
                return $component;
            }

            throw new InvalidConfigException('"' . $reference->id . '" refers to a ' . get_class($component) . " component. $type is expected.");
        }

        $valueType = is_object($reference) ? get_class($reference) : gettype($reference);
        throw new InvalidConfigException("Invalid data type: $valueType. $type is expected.");
    }


    public function get($container = null)
    {
        try {
            if ($container) {
                return $container->get($this->id);
            }
            if (Izi::$app && Izi::$app->has($this->id)) {
                return Izi::$app->get($this->id);
            }

            return Izi::$container->get($this->id);
        } catch (Exception $e) {
            if ($this->optional) {
                return null;
            }
            throw $e;
        }
    }

    public static function __set_state($state)
    {
        if (!isset($state['id'])) {
            throw new InvalidConfigException('Failed to instantiate class "Instance". Required parameter "id" is missing');
        }

        return new self($state['id']);
    }
}