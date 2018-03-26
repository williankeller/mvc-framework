<?php

/**
 * Request class.
 * It contains the request information and provide methods to fetch request body
 *
 * Copyright (C) 2018 MVC Framework.
 * This file included in MVC Framework is licensed under OSL 3.0
 *
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */
namespace Application\Core;

class Request
{
    /**
     * Set a list of trusted hosts patterns.
     * 
     * @var array
     */
    private static $trustedHostPatterns = [];

    /**
     * Array of parameters parsed from the URL.
     *
     * @var array
     */
    public $params = [
        'controller' => null,
        'action' => null,
        'args' => null
    ];

    /**
     * Array of POST data as well as uploaded files.
     *
     * @var array
     */
    public $data = [];

    /**
     * Array of querystring arguments
     *
     * @var array
     */
    public $query = [];

    /**
     * The URL used to make the request.
     *
     * @var string
     */
    public $url = null;

    /**
     * Constructor
     * Create a new request from PHP superglobals.
     *
     * @param array $config user provided config
     */
    public function __construct($config = [])
    {
        $this->data   = $this->mergeData($_POST, $_FILES);
        $this->query  = $_GET;
        $this->url    = $this->fullUrl();
        $this->params += isset($config['params']) ? $config['params'] : [];
    }

    /**
     * Merge post and files data
     * You shouldn't have two fields with the same 'name' attribute in $_POST & $_FILES
     *
     * @param  array $post
     * @param  array $files
     * @return array the merged array
     */
    private function mergeData(array $post, array $files)
    {
        foreach ($post as $key => $value) {
            if (is_string($value)) {
                $post[$key] = trim($value);
            }
        }
        return array_merge($files, $post);
    }

    /**
     * Count fields in $this->data and optionally exclude some fields
     *
     * @param  array   $exclude
     * @return mixed
     */
    public function countData(array $exclude = [])
    {
        $count = count($this->data);
        foreach ($exclude as $field) {
            if (array_key_exists($field, $this->data)) {
                $count--;
            }
        }
        return $count;
    }

    /**
     * Safer and better access to $this->data
     *
     * @param  string   $key
     * @return mixed
     */
    public function data($key)
    {
        return array_key_exists($key, $this->data) ? $this->data[$key] : null;
    }

    /**
     * Safer and better access to $this->query
     *
     * @param  string   $key
     * @return mixed
     */
    public function query($key)
    {
        return array_key_exists($key, $this->query) ? $this->query[$key] : null;
    }

    /**
     * Safer and better access to $this->params.
     *
     * @param  string   $key
     * @return mixed
     */
    public function param($key)
    {
        return array_key_exists($key, $this->params) ? $this->params[$key] : null;
    }

    /**
     * Detect if request is Ajax.
     *
     * @return boolean
     */
    public function isAjax()
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            return strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        }
        return false;
    }

    /**
     * Detect if request is POST request.
     *
     * @return boolean
     */
    public function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Detect if request is GET request.
     *
     * @return boolean
     */
    public function isGet()
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    /**
     * Detect if request over secured connection(SSL).
     *
     * @return boolean
     */
    public function isSSL()
    {
        return isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    }

    /**
     * Add parameters to $this->params.
     *
     * @param  array $params
     * @return Request
     */
    public function addParams(array $params)
    {
        $this->params = array_merge($this->params, $params);
        return $this;
    }

    /**
     * Get the current URI of the request.
     *
     * @return string|null
     */
    public function uri()
    {
        return isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null;
    }

    /**
     * Get the current host of the request
     *
     * @return string
     * @throws UnexpectedValueException if the hostname is invalid
     */
    public function host()
    {
        if (!$host = $this->environment('HTTP_HOST')) {
            if (!$host = $this->name()) {
                $host = $this->environment('SERVER_ADDR');
            }
        }
        // Trim and remove port number from host.
        $host = strtolower(preg_replace('/:\d+$/', '', trim($host)));

        // Check the hostname against a trusted list of host
        // patterns to avoid host header injection attacks.
        if (count(self::$trustedHostPatterns) > 0) {
            foreach (self::$trustedHostPatterns as $pattern) {
                if (preg_match($pattern, $host)) {
                    return $host;
                }
            }
        }
        return $host;
    }
    
    /**
     * Gets an environment variable from $_SERVER, $_ENV, or using getenv()
     *
     * @param $key string
     * @return string|null
     */
    public static function environment($key)
    {
        if (isset($_SERVER[$key])) {
            return $_SERVER[$key];
        }
        elseif (isset($_ENV[$key])) {
            return $_ENV[$key];
        }
        elseif (getenv($key) !== false) {
            return  getenv($key);
        }
        return null;
    }

    /**
     * Get the name of the server host
     *
     * @return string|null
     */
    public function name()
    {
        return isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : null;
    }

    /**
     * Get the referer of this request.
     *
     * @return string|null
     */
    public function referer()
    {
        return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
    }

    /**
     * get the client IP addresses.
     *
     * 'REMOTE_ADDR' is the most trusted one,
     * otherwise you can use HTTP_CLIENT_IP, or HTTP_X_FORWARDED_FOR.
     *
     * @return string|null
     */
    public function clientIp()
    {
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
    }

    /**
     * get the contents of the User Agent
     *
     * @return string|null
     */
    public function userAgent()
    {
        return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
    }

    /**
     * Gets the request's protocol.
     *
     * @return string
     */
    public function protocol()
    {
        return $this->isSSL() ? 'https' : 'http';
    }

    /**
     * Gets the protocol and HTTP host.
     *
     * @return string The protocol and the host
     */
    public function getProtocolAndHost()
    {
        return $this->protocol() . '://' . $this->host();
    }

    /**
     * Get the full URL for the request with the added query string parameters.
     *
     * @return string
     */
    public function fullUrl()
    {
        // Get uri
        $uri = $this->uri();
        if (strpos($uri, '?') !== false) {
            list($uri) = explode('?', $uri, 2);
        }

        // Add querystring arguments(neglect 'url' & 'redirect')
        $query    = '';
        $queryArr = $this->query;
        unset($queryArr['url']);
        unset($queryArr['redirect']);

        if (!empty($queryArr)) {
            $query .= '?' . http_build_query($queryArr, null, '&');
        }
        return $this->getProtocolAndHost() . $uri . $query;
    }

    /**
     * Get the full URL for the request without the protocol.
     * It could be useful to force a specific protocol.
     *
     * @return string
     */
    public function fullUrlWithoutProtocol()
    {
        return preg_replace('#^https?://#', '', $this->fullUrl());
    }

    /**
     * Returns the base URL.
     *
     * @return string
     */
    public function getBaseUrl()
    {
        $baseUrl = str_replace(
            ['public', '\\'],
            ['', '/'],
            dirname($this->environment('SCRIPT_NAME'))
        );
        return $baseUrl;
    }

    /**
     * Get the root URL for the application.
     *
     * @return string
     */
    public function root()
    {
        return $this->getProtocolAndHost() . $this->getBaseUrl();
    }
}
