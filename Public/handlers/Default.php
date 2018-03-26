<?php

/**
 * This file contains configuration for the application.
 *
 * Copyright (C) 2018 MVC Framework.
 * This file included in MVC Framework is licensed under OSL 3.0
 *
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */
return [
    /**
     * Configure default site routes.
     *
     */
    'PATH' => 'http://www.magestat.local/',
    'STATIC' => 'http://static.magestat.local/',

    /**
     * Configure default site Content.
     *
     */
    'LANGUAGE' => 'en',
    'TITLE' => '',
    'DESCRIPTION' => '',
    'ROBOTS' => 'index,follow',
    'KEYWORDS' => '',

    /**
     * Configuration for: Database Connection
     * Define database constants to establish a connection.
     *
     */
    'DB_HOST' => 'localhost',
    'DB_NAME' => 'database',
    'DB_USER' => 'root',
    'DB_PASS' => '',
    'DB_CHAR' => 'utf8',

    /**
     * Configuration for: Caches
     * Define path and cache enabled.
     */
    'CACHE_ENABLED' => true,
    'CACHE_PATH'    => APPLICATION . '/View/Cache',

    /**
     * Configuration for: Paths
     * Paths from views directory
     */
    'SECTIONS' => APPLICATION . '/View/Sections',
    'CONTENTS' => APPLICATION . '/View/Contents',

    /**
     * Configuration for: Cookies
     *
     */
    'COOKIE_EXPIRY' => 1209600,
    'SESSION_COOKIE_EXPIRY' => 604800,
    'COOKIE_DOMAIN' => '',
    'COOKIE_PATH' => '/',
    'COOKIE_SECURE' => false,
    'COOKIE_HTTP' => true,
    'COOKIE_SECRET_KEY' => 'af&70-GF^!a{f64r5@g38l]#kQ4B+43%',
];
