<?php

/*
 * Copyright (C) 2015 wkeller
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class Model {
    
    /*
     * Function to load model
     * @param varchar $model
     * @return object (Class name)
     */
    public function load($model = false) {

        // Just a file have be send
        if (!$model) {
            return;
        }
        
        // Change the model name to lowercase
        $name = strtolower($model);

        // File path
        $path = SYS_PATH . 'models/' . $name . '.php';

        // Verifica se o arquivo existe
        if (file_exists($path)) {

            // Include the file
            require_once $path;

            /* @var $model_name type */
            if (class_exists($name)) {

                // Return class object
                return new $name();
            }
            return;
        }
    }

    /*
     * Validate en clear up URL
     * @param char $url
     * @return rtrim url
     */
    public function clearURL($url) {

        if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {

            return false;
        }
        return rtrim($url, "/");
    }

    /*
     * Enconde json values
     */
    public function jsonEncode($str) {

        $a = array('"{\"', '\":\"');
        $b = array('{"', '":"');
        $c = json_encode($str);
        $d = str_replace($a, $b, $c);

        return $d;
    }

    /*
     * Verify if ajax request
     */
    final public function IsAJAX() {

        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
    }

}
