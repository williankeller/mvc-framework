<?php

/**
 * This file contains configuration for the application.
 *
 * Copyright (C) 2018 MVC Framework.
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */
return [
    /**
     * Configure default site Ccontent
     *
     */
    'LANGUAGE' => 'en',
    'TITLE' => '',
    'DESCRIPTION' => '',

    /**
     * Configuration for: Database Connection
     * Define database constants to establish a connection.
     *
     */
    'DB_HOST' => 'localhost',
    'DB_NAME' => 'database',
    'DB_USER' => 'root',
    'DB_PASS' => '',
    'DB_CHARSET' => 'utf8',

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

    /**
     * Configuration for: Encryption Keys
     *
     */
    'ENCRYPTION_KEY' => '3¥‹a0cd@!$251Êìcef08%&',
    'HMAC_SALT' => 'a8C7n7^Ed0%8Qfd9K4m6d$86Dab',
    'HASH_KEY' => 'z4D8Mp7Jm5cH',

    /**
     * Configuration for: Hashing strength
     *
     * It defines the strength of the password hashing/salting. '10' is the default value by PHP.
     * @see http://php.net/manual/en/function.password-hash.php
     *
     */
    'HASH_COST_FACTOR' => '10',

    /**
     * Configuration for: Pagination
     *
     */
    'PAGINATION_DEFAULT_LIMIT' => 10
];
