<?php

/**
 * Handler class.
 * Sets and Gets a configuration values.
 *
 * Copyright (C) 2018 MVC Framework.
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace Application\Core;

class Handler
{

    /**
     * Array of configurations
     *
     * @var array
     */
    private static $config = [];

    /**
     * Prefixes used to load specific configurations.
     *
     * @var array
     */
    private static $prefix = [
        'default' => 'Default',
        'js' => 'Javascript'
    ];

    /**
     * Get default configuration value(s)
     *
     * @param $key string
     * @return string|array|null
     */
    public static function get($key)
    {
        return self::_get($key, self::$prefix['default']);
    }

    /**
     * Set or add a default configuration value
     *
     * @param $key string
     */
    public static function set($key, $value)
    {
        self::_set($key, $value, self::$prefix['default']);
    }

    /**
     * Get javascript configuration value(s)
     *
     * @param $key string
     * @return string|array|null
     */
    public static function getScript($key = "")
    {
        return self::_get($key, self::$prefix['js']);
    }

    /**
     * Set or add a javascript configuration value
     *
     * @param string $key
     * @param mixed  $value
     */
    public static function setScript($key, $value)
    {
        self::_set($key, $value, self::$prefix['js']);
    }

    /**
     * Normalizes an array, and converts it to a standard format.
     *
     * @param  array $arr
     * @return array normalized array
     */
    public static function normalize($arr)
    {
        $keys   = array_keys($arr);
        $count  = count($keys);
        $newArr = [];

        for ($i = 0; $i < $count; $i++) {
            if (is_int($keys[$i])) {
                $newArr[$arr[$keys[$i]]] = null;
            }
            else {
                $newArr[$keys[$i]] = $arr[$keys[$i]];
            }
        }
        return $newArr;
    }

    /**
     * Cuts a string to the length of $length and replaces the last characters
     * with the ellipsis => '...' if the text is longer than length.
     *
     * @param  string $str
     * @param  string $len
     * @return string the truncated string
     */
    public function truncate($str, $len)
    {
        if (empty($str)) {
            return "";
        }
        else if (mb_strlen($str, 'UTF-8') > $len) {
            return mb_substr($str, 0, $len, "UTF-8") . " ...";
        }
        else {
            return mb_substr($str, 0, $len, "UTF-8");
        }
    }

    /**
     * Formats timestamp string coming from the database.
     *
     * @param  string  $timestamp MySQL TIMESTAMP
     * @return string  Date after formatting.
     */
    public function timestamp($timestamp, $format = "F j, Y")
    {
        $unixTime = strtotime($timestamp);

        $date = date($format, $unixTime);

        // What if date() failed to format? It will return false.
        return (empty($date)) ? "" : $date;
    }

    /**
     * Get a configuration value(s)
     *
     * @param $key string
     * @param $source string
     * @return string|null
     * @throws Exception if configuration file doesn't exist
     */
    private static function _get($key, $source)
    {
        if (!isset(self::$config[$source])) {
            $config_file = sprintf('%s/Core/Config/%s.php', APPLICATION, $source);

            if (!file_exists($config_file)) {
                return null;
            }
            self::$config[$source] = require $config_file . "";
        }

        if (empty($key)) {
            return self::$config[$source];
        }
        else if (isset(self::$config[$source][$key])) {
            return self::$config[$source][$key];
        }
        return null;
    }

    /**
     * Set or adds a configuration value
     *
     * @param $key string
     * @param $value string
     * @param $source string
     */
    private static function _set($key, $value, $source)
    {
        // load configurations if not already loaded
        if (!isset(self::$config[$source])) {
            self::_get($key, $source);
        }
        if ($key && $source) {
            self::$config[$source][$key] = $value;
        }
    }

}
