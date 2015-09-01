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

/*
 * Displa all errors
 */
ini_set('display_errors', 'On');

/*
 * Define global url
 */
define("URL", 'http://projects.mvc/');


/*
 * Define if cache is active
 * IMPORTANT: Disable this content on dinamically content
 */
define("CACHE", true);

/*
 * Define global app path
 */
define("APP_PATH", $_SERVER['DOCUMENT_ROOT'] . '/');

/*
 * Define root path
 */
define("ROOT_PATH", APP_PATH);

/*
 * Define path of core
 */
define("CORE_PATH", APP_PATH . 'core/');

/*
 * Define path of system
 */
define("SYS_PATH", APP_PATH . 'system/');

/*
 * Require autoload file
 */
require_once 'Autoload.php';

/*
 * Try start base router
 */
try {
    // Figure out the URL pattern, and instantiate the application
    Router::init();
    
} catch (Exception $e) {
    
    // Return error case exception exists
    print '<pre>' . $e->getMessage();
    print '<p>' . $e->getTraceAsString();
    
    exit;
}