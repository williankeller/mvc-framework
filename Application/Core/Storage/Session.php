<?php

/**
 * Session Class.
 *
 * Copyright (C) 2018 MVC Framework.
 * This file included in MVC Framework is licensed under OSL 3.0
 *
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace Application\Core\Storage;

use Application\Core\Handler;

class Session
{
    /**
     * constructor for Session Object.
     *
     * @access private
     */
    private function __construct() {}

    /**
     * Starts the session if not started yet.
     *
     * @access public
     */
    public static function init()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Set session key and value
     *
     * @access public
     * @static static method
     * @param $key
     * @param $value
     *
     */
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get session value by $key
     *
     * @access public
     * @static static method
     * @param  $key
     * @return mixed
     *
     */
    public static function get($key)
    {
        return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : null;
    }

    /**
     * Get session value by $key and destroy it
     *
     * @access public
     * @static static method
     * @param  $key
     * @return mixed
     *
     */
    public static function getAndDestroy($key)
    {
        if (array_key_exists($key, $_SESSION)) {
            $value = $_SESSION[$key];

            $_SESSION[$key] = null;
            unset($_SESSION[$key]);

            return $value;
        }
        return null;
    }

    /**
     * Reset session id, delete session file on server, and re-assign the values.
     *
     * @access public
     * @static static method
     * @param  array  $data
     * @return string
     *
     */
    public static function reset($data)
    {
        // remove old and regenerate session ID.
        session_regenerate_id(true);
        $_SESSION = array();

        // Save these values in the session,
        // they are needed to avoid session hijacking and fixation.
        $_SESSION['ip']             = $data['ip'];
        $_SESSION['user_agent']     = $data['user_agent'];
        $_SESSION['generated_time'] = time();

        // Set session cookie setting manually.
        setcookie(
            session_name(),
            session_id(),
            time() + Handler::get('SESSION_COOKIE_EXPIRY') /* a week */,
            Handler::get('COOKIE_PATH'),
            Handler::get('COOKIE_DOMAIN'),
            Handler::get('COOKIE_SECURE'),
            Handler::get('COOKIE_HTTP')
        );
    }

    /**
     * Remove session
     * Delete session completely from the browser cookies and destroy it's file on the server
     *
     * @access public
     * @static static method
     */
    public static function remove()
    {
        // clear session data
        $_SESSION = array();

        // remove session cookie
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        // destroy session file on server(if not already)
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }
}
