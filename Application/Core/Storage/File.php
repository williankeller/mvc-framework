<?php

/**
 * Stores data within the file system.
 *
 * Copyright (C) 2018 MVC Framework.
 * This file included in MVC Framework is licensed under OSL 3.0
 *
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace Application\Core\Storage;

use Application\Core\Handler;
use Application\Core\Request;

class File
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
        $filePath = Handler::get('CACHE_PATH') . $variable;

        if (
            // Unique content possible.
            Request::environment('REQUEST_METHOD') == 'POST' ||
            // Caching disabled.
            !Handler::get('CACHE_ENABLED') ||
            // Cache entry does not exist.
            !file_exists($filePath)
        ) {
            return false;
        }
        // Check the time the item was created to see if it is stale
        return (Request::environment('REQUEST_TIME') - filemtime($filePath)) <= Handler::get('SESSION_COOKIE_EXPIRY');
    }

    /**
     * Store a variable for use.
     *
     * @access public
     * @param  string  $variable  The name of the variable to store.
     * @param  mixed   $value     The data we wish to store.
     * @param  boolean $overwrite Whether we are allowed to overwrite the variable.
     * @return boolean            If we managed to store the variable.
     * @throws Exception          If the variable already exists when we try not to overwrite it.
     */
    public function put($variable, $value, $overwrite = false)
    {
        // If it exists, and we do not want to overwrite, then throw exception
        if ($this->has($variable) && !$overwrite) {
            return false;
        }

        file_put_contents(
            Handler::get('CACHE_PATH') . $variable, $value
        );
    }

    /**
     * Return the variable's value from the store.
     *
     * @access public
     * @param  string $variable The name of the variable in the store.
     * @return bool
     * @throws Exception        If the variable does not exist.
     */
    public function get($variable)
    {
        if (!$this->has($variable)) {
            return false;
        }
        // Return content.
        return file_get_contents(
            Handler::get('CACHE_PATH') . $variable
        );
    }

    /**
     * Remove the variable in the store.
     *
     * @access public
     * @param  string $variable The name of the variable to remove.
     * @return boolean          If the variable was removed successfully.
     * @throws Exception        If the variable does not exist.
     */
    public function remove($variable)
    {
        if ($this->has($variable)) {
            return unlink(Handler::get('CACHE_PATH') . $variable);
        }
    }

}
