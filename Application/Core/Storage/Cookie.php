<?php

/**
 * Cookie Class.
 *
 * Copyright (C) 2018 MVC Framework.
 * This file included in MVC Framework is licensed under OSL 3.0
 *
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace Application\Core\Storage;

class Cookie
{

    /**
     * Check whether the variable exists in the store.
     *
     * @access public
     * @param  string  $variable The name of the variable to check existence of.
     * @return boolean           If the variable exists or not.
     */
    public function has($variable)
    {
        return isset($_COOKIE[$variable]);
    }

    /**
     * Store a variable for use.
     *
     * @access public
     * @param  string  $variable  The name of the variable to store.
     * @param  mixed   $value     The data we wish to store.
     * @param  int     $expires   How many seconds the cookie should be kept.
     * @param  boolean $overwrite Whether we are allowed to overwrite the variable.
     * @return boolean            If we managed to store the variable.
     */
    public function put($variable, $value, $expires = 315360000)
    {
        // If it exists, and we want to overwrite.
        setcookie($variable, $value, time() + $expires, '/');
    }

    /**
     * Return the variable's value from the store.
     *
     * @access public
     * @param  string $variable The name of the variable in the store.
     * @return mixed
     */
    public function get($variable)
    {
        if ($this->has($variable)) {
            return $_COOKIE[$variable];
        }
        return NULL;
    }

    /**
     * Remove the variable in the store.
     *
     * @access public
     * @param  string $variable The name of the variable to remove.
     * @throws Exception        If the variable does not exist.
     */
    public function remove($variable)
    {
        if ($this->has($variable)) {
            // Remove the cookie by setting its expires in the past.
            setcookie($variable, '', (time() - 3600), '/');
        }
    }
}
