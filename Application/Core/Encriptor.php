<?php

/**
 * Encryptor and Decryption Class
 *
 * Copyright (C) 2018 MVC Framework.
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace Application\Core;

class Encryptor
{

    /**
     * Cipher algorithm
     *
     * @var string
     */
    const CIPHER = 'aes-256-cbc';

    /**
     * Hash function
     *
     * @var string
     */
    const HASH_FUNCTION = 'sha256';

    /**
     * constructor for Encryptor object.
     *
     * @access private
     */
    private function __construct();

    /**
     * Encrypt an id.
     *
     * @access public
     * @static static method
     * @param  integer|string	$id
     * @return string
     * @see http://kvz.io/blog/2009/06/10/create-short-ids-with-php-like-youtube-or-tinyurl/
     */
    public static function encryptId($id)
    {
        $encryptId = "";
        $chars     = self::getCharacters();
        $base      = strlen($chars);
        $id        = ((int) $id * 9518436) + 1142;

        for ($t = ($id != 0 ? floor(log($id, $base)) : 0); $t >= 0; $t--) {
            $bcp       = bcpow($base, $t);
            $a         = floor($id / $bcp) % $base;
            $encryptId = $encryptId . substr($chars, $a, 1);
            $id        = $id - ($a * $bcp);
        }
        return $encryptId;
    }

    /**
     * Decryption for Id.
     *
     * @access public
     * @static static method
     * @param  string	$id
     * @return bool|integer
     */
    public static function decryptId($id)
    {
        if (empty($id)) {
            return false;
        }

        $decryptId = 0;
        $chars     = self::getCharacters();
        $base      = strlen($chars);
        $len       = strlen($id) - 1;

        for ($t = $len; $t >= 0; $t--) {
            $bcp       = bcpow($base, $len - $t);
            $decryptId += strpos($chars, substr($id, $t, 1)) * (int) $bcp;
        }
        return ((int) $decryptId - 1142) / 9518436;
    }

    /**
     * Decryption for Ids with dash '-', Example: "feed-km1chg3"
     *
     * @access public
     * @static static method
     * @param  string	$id
     * @return bool|integer
     */
    public static function decryptIdWithDash($id)
    {
        if (empty($id)) {
            return false;
        }

        $decryptId = 0;
        $chars     = self::getCharacters();
        $base      = strlen($chars);
        $id        = explode("-", $id)[1];

        $len = strlen($id) - 1;

        for ($t = $len; $t >= 0; $t--) {
            $bcp       = bcpow($base, $len - $t);
            $decryptId = $decryptId + strpos($chars, substr($id, $t, 1)) * (int) $bcp;
        }
        return ((int) $decryptId - 1142) / 9518436;
    }

    /**
     * Get characters that will be used in Encryptor/decryption provided by a key
     *
     * @access private
     * @static static method
     * @return string
     * @throws Exception if $id is empty
     */
    private static function getCharacters()
    {
        $chars = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $i = [];
        for ($n = 0; $n < strlen($chars); $n++) {
            $i[] = substr($chars, $n, 1);
        }

        $key_hash = hash('sha256', Config::get('HASH_KEY'));
        $key_hash = (strlen($key_hash) < strlen($chars) ? hash('sha512', Config::get('HASH_KEY')) : $key_hash);

        for ($n = 0; $n < strlen($chars); $n++) {
            $p[] = substr($key_hash, $n, 1);
        }

        array_multisort($p, SORT_DESC, $i);

        return implode($i);
    }
}
