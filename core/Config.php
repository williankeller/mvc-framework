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
 * Define global url
 */
define("URL", 'http://projects.mvc/');

/*
 * Displa all errors
 */
ini_set('display_errors', 'On');

/*
 * Define if cache is active
 * IMPORTANT: Disable this content on dinamically content
 */
define("CACHE", FALSE);

/*
 * Database settings
 * @DB_HOST @DB_NAME, @DB_USER, @DB_PASS, @DB_DBUG
 */
/*
 * @DB_HOST
 */
define("DB_HOST", 'localhost');

/*
 * @DB_NAME
 */
define("DB_NAME", 'littlemvc');

/*
 * @DB_USER
 */
define("DB_USER", 'root');

/*
 * @DB_PASS
 */
define("DB_PASS", '');

/*
 * @DB_DBUG
 */
define("DB_DBUG", false);

/*
 * IMPORTANT
 * Please no change nothing more
 * Here have super defines and any change can destroy the application
 * Thanks!
 */
/*
 * Define root path
 */
define("ROOT_PATH", APP_PATH);

/*
 * Define path of core
 */
define("CORE_PATH", APP_PATH . 'core' . DIRECTORY_SEPARATOR);

/*
 * Define path of system
 */
define("SYS_PATH", APP_PATH . 'system' . DIRECTORY_SEPARATOR);

/*
 * Require autoload file
 */
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'Autoload.php');

/*
 * Try start base router
 */
try {
    // Figure out the URL pattern, and instantiate the application
    Router::init();
    
    /*
     * Catch error
     */
} catch (Exception $e) {

    // Return error case exception exists
    print '<pre>' . $e->getMessage();
    print '<p>' . $e->getTraceAsString();

    exit;
}