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

$autoloaderPaths = Array(
    CORE_PATH,
    SYS_PATH . 'controllers/',
    SYS_PATH . 'models/',
);

/**
 * Search for classes in all include path locations.
 */
//$autoloaderPaths = array_unique(array_merge($autoloaderPaths, explode(PATH_SEPARATOR, get_include_path())));

/**
 * This is the autoloader method responsible for finding and all the classes
 * Search Class.php file and require it
 * @param string $class
 */
function autoloader($class) {

    global $autoloaderPaths;

    $file = null;

    /**
     * Search in all autoloader locations for the class
     */
    foreach ($autoloaderPaths as $path) {

        $filename = $path . ucfirst(strtolower($class)) . '.php';

        if (file_exists($filename)) {
            
            $file = $filename;
            break;
        }
    }

    if (is_null($file)) {

        throw new Exception('Autoloader could not find class - ' . $class . '.php');
    } else {
        require $file;
    }
}

/**
 * Registers custom autoloader method
 */
spl_autoload_register("autoloader");
