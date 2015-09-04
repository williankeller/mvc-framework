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

class Translate {
    /*
     * Define default language
     */

    private $language = 'en-us';

    /*
     * Array lang to search
     */
    private $lang = array();

    /*
     * Define default language separator
     */
    private $separator = "=";

    /*
     * Define land path files
     */
    private $path;

    /*
     * Construct function to define language route
     */

    public function __construct() {

        $this->path = SYS_PATH . 'language/';

        $this->language = $this->path . $this->language;

        $this->getLang();
    }

    /*
     * Function to find string in file content
     */

    private function findString($str) {

        if (array_key_exists($str, $this->lang[$this->language])) {

            return $this->lang[$this->language][$str];
        }
        return $str;
    }

    /*
     * Function separate contente to translate
     */
    private function splitStrings($str) {

        return explode($this->separator, trim($str));
    }
    
    /*
     * Get actions to define cookie
     */
    private function getLang() {
        
        /*
         * Detect browser cookie
         */
        $langDetect = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 5));
        
        /*
         * Case lang has detected in browser
         */
        if ($langDetect) {

            $this->language = $this->path . $langDetect;
        }
        /*
         * Case lang has detected in url action
         */
        if (Router::getAction() == 'lang') {

            $this->language = $this->path . Router::getParam();
        }
        /*
         * Case lang has detected in cookie
         */
        else if (isset($_COOKIE['_lmvc_lang'])) {

            $this->language = $this->path . $_COOKIE['_lmvc_lang'];
        }
        
        #$this->setLang($this->language);
    }
    
    /*
     * Set Cookie lang at cookie
     */
    public function setLang($lang) {

        setcookie('_lmvc_lang', $lang, time() + (10 * 365 * 24 * 60 * 60), '/'); // Expire in one month

        $this->language = $this->path . $_COOKIE['_lmvc_lang'];
        
        header("Location:" . $_SERVER['HTTP_REFERER']);
    }

    /*
     * Function to translate action
     */
    public function __($str) {

        if (!array_key_exists($this->language, $this->lang)) {

            if (file_exists($this->language . '.txt')) {
                
                /*
                 * Map on separated strings
                 */
                $strings = array_map(array($this, 'splitStrings'), file($this->language . '.txt'));
                
                foreach ($strings as $k => $v) {

                    $this->lang[$this->language][$v[0]] = $v[1];
                }
                return $this->findString($str);
            } else {
                return $str;
            }
        } else {
            return $this->findString($str);
        }
    }

}
