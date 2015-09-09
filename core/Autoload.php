<?php

/**
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
$namespaces = array(
    CORE_PATH,
    SYS_PATH . 'controllers',
    SYS_PATH . 'models'
);

/*
 * Autoload Function
 */
function autoloader($class) {
    
    /*
     * Define namespaces with global
     */
    global $namespaces;
    
    /*
     * Each namespaces
     */
    foreach ($namespaces as $namespace) {
        
        /*
         * Search for the class file in our namespaces
         */
        $path = $namespace .DIRECTORY_SEPARATOR . str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
        
        /*
         * Verify if file exists
         */
        if (file_exists($path)) {

            require_once( $path );
            
            return;
        }
    }
}
/*
 * Register autoload function
 */
spl_autoload_register("autoloader");
