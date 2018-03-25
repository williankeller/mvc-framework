<?php

/**
 * Environment class.
 * Gets an environment variable from $_SERVER
 *
 * Copyright (C) 2018 MVC Framework.
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */
namespace Application\Core;

class Environment
{

    /**
     * Constructor
     *
     */
    private function __construct() {}

    /**
     * Gets an environment variable from $_SERVER, $_ENV, or using getenv()
     *
     * @param $key string
     * @return string|null
     */
    public static function get($key)
    {
        $val = null;
        if (isset($_SERVER[$key])) {
            $val = $_SERVER[$key];
        }
        elseif (isset($_ENV[$key])) {
            $val = $_ENV[$key];
        }
        elseif (getenv($key) !== false) {
            $val = getenv($key);
        }
        return $val;
    }

}
