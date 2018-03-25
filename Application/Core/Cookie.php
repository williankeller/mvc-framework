<?php

/**
 * Cookie Class
 *
 * Copyright (C) 2018 MVC Framework.
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace Application\Core;

class Cookie
{

    /**
     * @access public
     * @var string User ID
     */
    private static $userId = null;

    /**
     * @access public
     * @var string Cookie Token
     */
    private static $token = null;

    /**
     * @access public
     * @var string Hashed Token = hash(User ID . ":" . Token . Cookie Secret)
     */
    private static $hashedCookie = null;

    /**
     * This is the constructor for Cookie object.
     *
     * @access private
     */
    private function __construct();

    /**
     * Getters for $userId
     *
     * @access public
     * @static static method
     * @return string   User ID
     */
    public static function getUserId()
    {
        return (int) self::$userId;
    }

    /**
     * Extract and validate cookie
     *
     * @access public
     * @static static method
     * @return bool
     */
    public static function isCookieValid()
    {
        // "auth" or "remember me" cookie
        if (empty($_COOKIE['auth'])) {
            return false;
        }

        // Check the count before using explode
        $cookie_auth = explode(':', $_COOKIE['auth']);
        if (count($cookie_auth) !== 3) {
            self::remove();
            return false;
        }

        list ($encryptedUserId, self::$token, self::$hashedCookie) = $cookie_auth;

        // $hashedCookie was generated from the original user Id, NOT from the encrypted one.
        self::$userId = ($encryptedUserId);

        if (self::$hashedCookie === hash('sha256', self::$userId . ':' . self::$token . Handler::get('COOKIE_SECRET_KEY')) && !empty(self::$token) && !empty(self::$userId)) {
            return true;
        }
        self::remove(self::$userId);
        return false;
    }

    /**
     * Remove cookie from the database of a user(if exists),
     * and also from the browser.
     *
     * @static static  method
     * @param  string  $userId
     */
    public static function remove($userId = null)
    {
        self::$userId = self::$token = self::$hashedCookie = null;

        // How to kill/delete a cookie in a browser?
        setcookie('auth', false, time() - (3600 * 3650), Handler::get('COOKIE_PATH'), Handler::get('COOKIE_DOMAIN'), Handler::get('COOKIE_SECURE'), Handler::get('COOKIE_HTTP'));
    }

    /**
     * Reset Cookie,
     * resetting is done by updating the database,
     * and resetting the "auth" cookie in the browser
     *
     * @static  static method
     * @param   string $userId
     */
    public static function reset($userId)
    {
        self::$userId = $userId;
        self::$token  = hash('sha256', mt_rand());

        // generate cookie string(remember me)
        // Don't expose the original user id in the cookie, Encrypt It!
        $cookieFirstPart = self::$token;

        // $hashedCookie generated from the original user Id, NOT from the encrypted one.
        self::$hashedCookie = hash('sha256', self::$userId . ':' . self::$token . Handler::get('COOKIE_SECRET_KEY'));

        $authCookie = $cookieFirstPart . ':' . self::$hashedCookie;

        setcookie('auth', $authCookie, time() + Handler::get('COOKIE_EXPIRY'), Handler::get('COOKIE_PATH'), Handler::get('COOKIE_DOMAIN'), Handler::get('COOKIE_SECURE'), Handler::get('COOKIE_HTTP'));
    }

}
