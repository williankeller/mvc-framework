<?php

/**
 * The application class.
 * Handles the request for each call to the application.
 *
 * Copyright (C) 2018 MVC Framework.
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace Core;

class Application
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
            $controllerName = $this->controller;

            if (!self::isMethodValid($controllerName, $this->method)) {
                return $this->notFound();
            }
            if (!empty($this->method)) {
                if (!self::areArgsValid($controllerName, $this->method, $this->args)) {
                    return $this->notFound();
                }
                // finally instantiate the controller object, and call it's action method.
                return $this->invoke($controllerName, $this->method, $this->args);
            }
            else {
                $this->method = "index";
                if (!method_exists($controllerName, $this->method)) {
                    return $this->notFound();
                }

                return $this->invoke($controllerName, $this->method, $this->args);
            }
        }
        else {
            // If no controller defined,
            // then send to login controller, and it should take care of the request
            // either redirect to login page, or dashboard.
            $this->method = "index";
            return $this->invoke("LoginController", $this->method, $this->args);
        }
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

        $this->controller = new $controller($this->request, $this->response);

        $result = $this->controller->startupProcess();
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
        if (!empty($controller)) {
            if (!preg_match('/\A[a-z]+\z/i', $controller) ||
                strtolower($controller) === "errorscontroller" ||
                !file_exists(APP . 'controllers/' . $controller . '.php')) {
                return false;
            }
            else {
                return true;
            }
        }
        else {
            return true;
        }
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
        if (!empty($method)) {
            if (!preg_match('/\A[a-z]+\z/i', $method) ||
                !method_exists($controller, $method) ||
                strtolower($method) === "index") {
                return false;
            }
            else {
                return true;
            }
        }
        else {
            return true;
        }
    }

    /**
     * Split the URL for the current request.
     *
     */
    public function splitUrl()
    {
        $url = $this->request->query("url");

        if (!empty($url)) {

            $url = explode('/', filter_var(trim($url, '/'), FILTER_SANITIZE_URL));

            $this->controller = !empty($url[0]) ? ucwords($url[0]) . 'Controller' : null;
            $this->method     = !empty($url[1]) ? $url[1] : null;

            unset($url[0], $url[1]);

            $this->args = !empty($url) ? array_values($url) : [];
        }
    }

    /**
     * Shows not found error page
     */
    private function notFound()
    {
        return (new ErrorsController())->error(404)->send();
    }

}
