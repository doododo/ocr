<?php

/*
 * This file is part of the doododo/ocr.
 *
 * (c) doododo <saybye720@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Doododo\OCR\Support;

use ArrayAccess;

class Config implements ArrayAccess
{
    protected $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * 用点获取配置项的值.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $config = $this->config;

        if (isset($config[$key])) {
            return $config[$key];
        }

        if (false === strpos($key, '.')) {
            return $default;
        }

        foreach (explode('.', $key) as $segment) {
            if (!is_array($config) || !array_key_exists($segment, $config)) {
                return $default;
            }
            $config = $config[$segment];
        }

        return $config;
    }

    /**
     * 检查一个偏移位置是否存在.
     *
     * @param mixed $offset
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->config);
    }

    /**
     * 获取一个偏移位置的值.
     *
     * @param mixed $offset
     *
     * @return mixed Can return all value types
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * 设置一个偏移位置的值.
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if (isset($this->config[$offset])) {
            $this->config[$offset] = $value;
        }
    }

    /**
     * 复位一个偏移位置的值.
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        if (isset($this->config[$offset])) {
            unset($this->config[$offset]);
        }
    }
}
