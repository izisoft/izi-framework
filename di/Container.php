<?php
/**
 * @link https://framework.iziweb.net
 * @copyright Copyright (c) 2021 Izi Software LLC
 * @license https://framework.iziweb.net/license
 */

namespace izi\di;

use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionParameter;
use Izi;
use izi\base\InvalidConfigException;

/**
 * Class Container
 *
 * @package izi\di
 * @author Giang A Tin <vantruong1898@gmail.com>
 * @since 1.0
 */
class Container extends \izi\base\Component
{
    private array $_reflections = [];

    private array $_dependencies = [];
    /**
     * @var bool whether to attempt to resolve elements in array dependencies
     */
    private bool $_resolveArrays = false;


    public function get($class, $params = [], $config = [])
    {
        if ($class instanceof Instance) {
            $class = $class->id;
        }
        if (isset($this->_singletons[$class])) {
            // singleton
            return $this->_singletons[$class];
        } elseif (!isset($this->_definitions[$class])) {
            return $this->build($class, $params, $config);
        }

        $definition = $this->_definitions[$class];

        if (is_callable($definition, true)) {
            $params = $this->resolveDependencies($this->mergeParams($class, $params));
            $object = call_user_func($definition, $this, $params, $config);
        } elseif (is_array($definition)) {
            $concrete = $definition['class'];
            unset($definition['class']);

            $config = array_merge($definition, $config);
            $params = $this->mergeParams($class, $params);

            if ($concrete === $class) {
                $object = $this->build($class, $params, $config);
            } else {
                $object = $this->get($concrete, $params, $config);
            }
        } elseif (is_object($definition)) {
            return $this->_singletons[$class] = $definition;
        } else {
            throw new InvalidConfigException('Unexpected object definition type: ' . gettype($definition));
        }

        if (array_key_exists($class, $this->_singletons)) {
            // singleton
            $this->_singletons[$class] = $object;
        }

        return $object;
    }


    protected function build($class, $params, $config)
    {
        /* @var $reflection ReflectionClass */
        list($reflection, $dependencies) = $this->getDependencies($class);

        $addDependencies = [];
        if (isset($config['__construct()'])) {
            $addDependencies = $config['__construct()'];
            unset($config['__construct()']);
        }
        foreach ($params as $index => $param) {
            $addDependencies[$index] = $param;
        }

        $this->validateDependencies($addDependencies);

        if ($addDependencies && is_int(key($addDependencies))) {
            $dependencies = array_values($dependencies);
            $dependencies = $this->mergeDependencies($dependencies, $addDependencies);
        } else {
            $dependencies = $this->mergeDependencies($dependencies, $addDependencies);
            $dependencies = array_values($dependencies);
        }

        $dependencies = $this->resolveDependencies($dependencies, $reflection);
        if (!$reflection->isInstantiable()) {
            throw new NotInstantiableException($reflection->name);
        }
        if (empty($config)) {
            return $reflection->newInstanceArgs($dependencies);
        }

        $config = $this->resolveDependencies($config);

        if (!empty($dependencies) && $reflection->implementsInterface('izi\base\Configurable')) {
            // set $config as the last parameter (existing one will be overwritten)
            $dependencies[count($dependencies) - 1] = $config;
            return $reflection->newInstanceArgs($dependencies);
        }

        $object = $reflection->newInstanceArgs($dependencies);
        foreach ($config as $name => $value) {
            $object->$name = $value;
        }

        return $object;
    }


    protected function getDependencies($class)
    {
        if (isset($this->_reflections[$class])) {
            return [$this->_reflections[$class], $this->_dependencies[$class]];
        }

        $dependencies = [];
        try {
            $reflection = new ReflectionClass($class);
        } catch (\ReflectionException $e) {
            throw new NotInstantiableException(
                $class,
                'Failed to instantiate component or class "' . $class . '".',
                0,
                $e
            );
        }

        $constructor = $reflection->getConstructor();
        if ($constructor !== null) {
            foreach ($constructor->getParameters() as $param) {
                if (PHP_VERSION_ID >= 50600 && $param->isVariadic()) {
                    break;
                }

                if (PHP_VERSION_ID >= 80000) {
                    $c = $param->getType();
                    $isClass = false;
                    if ($c instanceof ReflectionNamedType) {
                        $isClass = !$c->isBuiltin();
                    }
                } else {
                    try {
                        $c = $param->getClass();
                    } catch (ReflectionException $e) {
                        if (!$this->isNulledParam($param)) {
                            $notInstantiableClass = null;
                            if (PHP_VERSION_ID >= 70000) {
                                $type = $param->getType();
                                if ($type instanceof ReflectionNamedType) {
                                    $notInstantiableClass = $type->getName();
                                }
                            }
                            throw new NotInstantiableException(
                                $notInstantiableClass,
                                $notInstantiableClass === null ? 'Can not instantiate unknown class.' : null
                            );
                        } else {
                            $c = null;
                        }
                    }
                    $isClass = $c !== null;
                }
                $className = $isClass ? $c->getName() : null;

                if ($className !== null) {
                    $dependencies[$param->getName()] = Instance::of($className, $this->isNulledParam($param));
                } else {
                    $dependencies[$param->getName()] = $param->isDefaultValueAvailable()
                        ? $param->getDefaultValue()
                        : null;
                }
            }
        }

        $this->_reflections[$class] = $reflection;
        $this->_dependencies[$class] = $dependencies;

        return [$reflection, $dependencies];
    }


    private function validateDependencies($parameters)
    {
        $hasStringParameter = false;
        $hasIntParameter = false;
        foreach ($parameters as $index => $parameter) {
            if (is_string($index)) {
                $hasStringParameter = true;
                if ($hasIntParameter) {
                    break;
                }
            } else {
                $hasIntParameter = true;
                if ($hasStringParameter) {
                    break;
                }
            }
        }
        if ($hasIntParameter && $hasStringParameter) {
            throw new InvalidConfigException(
                'Dependencies indexed by name and by position in the same array are not allowed.'
            );
        }
    }

    private function mergeDependencies($a, $b)
    {
        foreach ($b as $index => $dependency) {
            $a[$index] = $dependency;
        }
        return $a;
    }

    protected function resolveDependencies($dependencies, $reflection = null)
    {
        foreach ($dependencies as $index => $dependency) {
            if ($dependency instanceof Instance) {
                if ($dependency->id !== null) {
                    $dependencies[$index] = $dependency->get($this);
                } elseif ($reflection !== null) {
                    $name = $reflection->getConstructor()->getParameters()[$index]->getName();
                    $class = $reflection->getName();
                    throw new InvalidConfigException("Missing required parameter \"$name\" when instantiating \"$class\".");
                }
            } elseif ($this->_resolveArrays && is_array($dependency)) {
                $dependencies[$index] = $this->resolveDependencies($dependency, $reflection);
            }
        }

        return $dependencies;
    }
}