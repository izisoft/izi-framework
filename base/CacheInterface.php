<?php
/**
 * @link https://framework.iziweb.net
 * @copyright Copyright (c) 2021 Izi Software LLC
 * @license https://framework.iziweb.net/license
 */

namespace izi\base;


interface CacheInterface extends \ArrayAccess
{
    public function buildKey($key);

    public function get($key);

    public function exists($key);

    public function multiGet($keys);

    public function set($key, $value, $duration = null, $dependency = null);

    public function multiSet($items, $duration = 0, $dependency = null);

    public function add($key, $value, $duration = 0, $dependency = null);

    public function multiAdd($items, $duration = 0, $dependency = null);

    public function delete($key);

    public function flush();

    public function getOrSet($key, $callable, $duration = null, $dependency = null);

}