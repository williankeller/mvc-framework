<?php

/**
 * The application class.
 * Handles the request for each call to the application.
 *
 * Copyright (C) 2018 MVC Framework.
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace Application\Core;

class Registry
{

    /**
     * controller
     * @var mixed
     */
    private $controller = null;

    /**
     * action method
     * @var string
     */
    private $method = null;

    /**
     * passed arguments
     * @var array
     */
    private $args = array();

    /**
     * request
     * @var Request
     */
    public $request = null;

    /**
     * response
     * @var Response
     */
    public $response = null;

    /**
     * application constructor
     *
     * @access public
     */
    public function __construct()
    {
        // initialize request and respond objects
        $this->request  = new Request();
        $this->response = new Response();
    }

    public function run()
    {
        // split the requested URL
        $this->splitUrl();

        if (!self::isControllerValid($this->controller)) {
            return $this->notFound();
        }
        if (!empty($this->controller)) {
            
        }

        // If no controller defined
        $this->method = 'index';
        return $this->invoke("Index", $this->method, $this->args);
    }

    /**
     * Build Controller route.
     *
     * @param string $controller
     * @return $this
     */
    private function buildControllerFunction($controller)
    {
        $function = '\\Application\\Controller\\' . $controller;

        $this->controller = new $function($this->request, $this->response);

        $result = $this->controller->startupProcess();

        return $result;
    }

    /**
     * Instantiate controller object and trigger it's action method
     *
     * @param  string $controller
     * @param  string $method
     * @param  array  $args
     * @return Response 
     */
    private function invoke($controller, $method = "index", $args = [])
    {
        $this->request->addParams([
            'controller' => $controller,
            'action' => $method,
            'args' => $args
        ]);
        // Build Controller route.
        $result = $this->buildControllerFunction($controller);

        if ($result instanceof Response) {
            return $result->send();
        }

        if (!empty($args)) {
            $response = call_user_func_array([$this->controller, $method], $args);
        }
        else {
            $response = $this->controller->{$method}();
        }

        if ($response instanceof Response) {
            return $response->send();
        }
        return $this->response->send();
    }

    /**
     * Detect if controller is valid
     * Any request to error controller will be considered as invalid,
     * because error pages will be rendered(even with ajax) from inside the application
     *
     * @param  string $controller
     * @return boolean
     */
    private static function isControllerValid($controller)
    {
        if (empty($controller)) {
            return true;
        }
        if (!preg_match('/\A[a-z]+\z/i', $controller) ||
            strtolower($controller) === "Errors") {
            return false;
        }
        if (!file_exists(APPLICATION . '/Controller/' . $controller . '.php')) {
            return false;
        }
        return true;
    }

    /**
     * Detect if action method is valid
     * Make a request to 'index' method will be considered as invalid,
     * the constructor will take care of index methods.
     *
     * @param string $controller
     * @param string $method
     * @return boolean
     */
    private static function isMethodValid($controller, $method)
    {
        if (empty($method)) {
            return true;
        }
        if (!preg_match('/\A[a-z]+\z/i', $method) ||
            !method_exists($controller, $method) ||
            strtolower($method) === 'index') {
            return false;
        }
        return true;
    }

    /**
     * Split the URL for the current request.
     */
    public function splitUrl()
    {
        $request = $this->request->query("url");

        if (empty($request)) {
            return false;
        }
        $url = explode('/', filter_var(trim($request, '/'), FILTER_SANITIZE_URL));

        $this->controller = !empty($url[0]) ? ucwords($url[0]) : null;
        $this->method     = !empty($url[1]) ? $url[1] : null;

        unset($url[0], $url[1]);

        $this->args = !empty($url) ? array_values($url) : [];
    }

    /**
     * Shows not found error page
     */
    private function notFound()
    {
        $this->invoke('Errors', 'page404');
    }

}
