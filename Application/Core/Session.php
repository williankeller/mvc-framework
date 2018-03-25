<?php

/**
 * Session Class
 *
 * Copyright (C) 2018 MVC Framework.
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace Application\Core;

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
     * Checks if session data exists and valid or not.
     *
     * @access public
     * @static static method
     * @param  string $ip
     * @param  string $userAgent
     * @return boolean
     */
    public static function isSessionValid($ip, $userAgent)
    {
        $isLoggedIn = self::getIsLoggedIn();
        $userId     = self::getUserId();
        $userRole   = self::getUserRole();

        // 1. check if there is any data in session
        if (empty($isLoggedIn) || empty($userId) || empty($userRole)) {
            return false;
        }

        // 2. then check ip address and user agent
        if (!self::validateIPAddress($ip) || !self::validateUserAgent($userAgent)) {
            self::remove();
            return false;
        }

        // 3. check if session is expired
        if (!self::validateSessionExpiry()) {
            self::remove();
            return false;
        }

        return true;
    }

    /**
     * Get IsLoggedIn value(boolean)
     *
     * @access public
     * @static static method
     * @return boolean
     *
     */
    public static function getIsLoggedIn()
    {
        return empty($_SESSION["is_logged_in"]) || !is_bool($_SESSION["is_logged_in"]) ? false : $_SESSION["is_logged_in"];
    }

    /**
     * Get User ID.
     *
     * @access public
     * @static static method
     * @return string|null
     *
     */
    public static function getUserId()
    {
        return empty($_SESSION["user_id"]) ? null : (int) $_SESSION["user_id"];
    }

    /**
     * Get User Role
     *
     * @access public
     * @static static method
     * @return string|null
     *
     */
    public static function getUserRole()
    {
        return empty($_SESSION["role"]) ? null : $_SESSION["role"];
    }

    /**
     * Get CSRF Token
     *
     * @access public
     * @static static method
     * @return string|null
     *
     */
    public static function getCsrfToken()
    {
        return empty($_SESSION["csrf_token"]) ? null : $_SESSION["csrf_token"];
    }

    /**
     * Get CSRF Token generated time
     *
     * @access public
     * @static static method
     * @return string|null
     *
     */
    public static function getCsrfTokenTime()
    {
        return empty($_SESSION["csrf_token_time"]) ? null : $_SESSION["csrf_token_time"];
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
     * Matches current IP Address with the one stored in the session
     *
     * @access public
     * @static static method
     * @param  string $ip
     * @return bool
     */
    private static function validateIPAddress($ip)
    {
        if (!isset($_SESSION['ip']) || !isset($ip)) {
            return false;
        }
        return $_SESSION['ip'] === $ip;
    }

    /**
     * Matches current user agent with the one stored in the session
     *
     * @access public
     * @static static method
     * @param  string $userAgent
     * @return bool
     *
     */
    private static function validateUserAgent($userAgent)
    {
        if (!isset($_SESSION['user_agent']) || !isset($userAgent)) {
            return false;
        }
        return $_SESSION['user_agent'] === $userAgent;
    }

    /**
     * Checks if session has been expired
     *
     * @access public
     * @static static method
     * @return bool
     *
     */
    private static function validateSessionExpiry()
    {
        $max_time = 60 * 60 * 24; // 1 day

        if (!isset($_SESSION['generated_time'])) {
            return false;
        }
        return ($_SESSION['generated_time'] + $max_time) > time();
    }

    /**
     * Get CSRF token and generate a new one if expired
     *
     * @access public
     * @static static method
     * @return string
     *
     */
    public static function generateCsrfToken()
    {
        $max_time    = 60 * 60 * 24; // 1 day
        $stored_time = self::getCsrfTokenTime();
        $csrf_token  = self::getCsrfToken();

        if ($max_time + $stored_time <= time() || empty($csrf_token)) {
            $_SESSION["csrf_token"]      = md5(uniqid(rand(), true));
            $_SESSION["csrf_token_time"] = time();
        }
        return self::getCsrfToken();
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

        $_SESSION["is_logged_in"] = true;
        $_SESSION["user_id"]      = (int) $data["user_id"];
        $_SESSION["role"]         = $data["role"];

        // save these values in the session,
        // they are needed to avoid session hijacking and fixation
        $_SESSION['ip']             = $data["ip"];
        $_SESSION['user_agent']     = $data["user_agent"];
        $_SESSION['generated_time'] = time();

        // Set session cookie setting manually
        setcookie(session_name(), session_id(), time() + Config::get('SESSION_COOKIE_EXPIRY') /* a week */, Config::get('COOKIE_PATH'), Config::get('COOKIE_DOMAIN'), Config::get('COOKIE_SECURE'), Config::get('COOKIE_HTTP'));
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
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        }

        // destroy session file on server(if not already)
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }

}
