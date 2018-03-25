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

        if (!$this->isControllerValid($this->controller)) {
            return $this->notFound();
        }

        if (empty($this->controller)) {
            $this->controller = 'Index';
        }

        if (empty($this->method)) {
            $this->method = 'index';
        }

        if (!$this->isMethodValid($this->controller, $this->method)) {
            return $this->notFound();
        }
        return $this->invoke($this->controller, $this->method, $this->args);
    }

    /**
     * Build Controller route.
     *
     * @param string $controller
     * @return $this
     */
    private function buildControllerFunction($controller, $isNamespace = false)
    {
        $function = '\\Application\\Controller\\' . $controller;

        if ($isNamespace) {
            return $function;
        }
        $this->controller = new $function($this->request, $this->response);
    }

    /**
     * Instantiate controller object and trigger it's action method
     *
     * @param  string $controller
     * @param  string $method
     * @param  array  $args
     * @return Response 
     */
    private function invoke($controller, $method, $args = [])
    {
        $this->request->addParams([
            'controller' => $controller,
            'action' => $method,
            'args' => $args
        ]);
        // Build Controller route.
        $this->buildControllerFunction($controller);

        $result = $this->controller->startupProcess();
        if ($result instanceof Response) {
            return $result->send();
        }

        if (empty($args)) {
            $response = $this->controller->{$method}();
        }
        else {
            $response = call_user_func_array([$this->controller, $method], $args);
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
    private function isControllerValid($controller)
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
    private function isMethodValid($controller, $method)
    {
        if (empty($method)) {
            return true;
        }
        if (!preg_match('/\A[a-z]+\z/i', $method)) {
            return false;
        }

        $function = $this->buildControllerFunction($controller, true);
        if (!method_exists($function, $method)) {
            return false;
        }
        return true;
    }

    /**
     * Split the URL for the current request.
     */
    public function splitUrl()
    {
        $request = $this->request->query('url');

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
