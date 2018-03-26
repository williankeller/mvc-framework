<?php

/**
 * The controller class.
 * To extend for each controller.
 *
 * Copyright (C) 2018 MVC Framework.
 * This file included in MVC Framework is licensed under OSL 3.0
 *
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace Application\Core;

class Controller
{
    /**
     * view
     *
     * @var View
     */
    protected $view;

    /**
     * request
     *
     * @var Request
     */
    public $request;

    /**
     * response
     *
     * @var Response
     */
    public $response;

    /**
     * redirector
     *
     * @var Redirector
     */
    public $redirector;

    /**
     * Constructor
     *
     * @param Request  $request
     * @param Response $response
     */
    public function __construct(
        Request $request = null,
        Response $response = null
    ) {
        $this->request    = $request !== null ? $request : new Request();
        $this->response   = $response !== null ? $response : new Response();
        $this->view       = new View($this);
        $this->redirector = new Redirector();
    }

    /**
     * Perform the startup process for this controller.
     * Events that that will be triggered for each controller:
     * 
     * @return void|Response
     */
    public function startupProcess()
    {
        $this->initialize();

        $this->beforeAction();
    }

    /**
     * Initialization method.
     * initialize components and optionally, assign configuration data
     *
     */
    public function initialize() {}

    /**
     * show error page
     *
     * call error action method and set response status code
     * This will work as well for ajax call, see how ajax calls are handled in main.js
     *
     * @param int|string $code
     *
     */
    public function error($code)
    {
        $errors = [
            404 => "notfound",
            401 => "unauthenticated",
            403 => "unauthorized",
            400 => "badrequest",
            500 => "system"
        ];

        if (!isset($errors[$code]) || !method_exists('Errors', $errors[$code])) {
            $code = 500;
        }

        $action = isset($errors[$code]) ? $errors[$code] : "System";
        $this->response->setStatusCode($code);

        // clear, get page, then send headers
        $this->response->clearBuffer();
        (new Errors($this->request, $this->response))->{$action}();

        return $this->response;
    }

    /**
     * Called before the controller action.
     * Used to perform logic that needs to happen before each controller action.
     */
    public function beforeAction()
    {
        // Insert default parameters to head.
        $this->defaultHeadParams();

        // Insert default parameters to content.
        $this->defaultContentParams();
    }

    /**
     * Default index to render index content.
     */
    public function index()
    {
        $this->view->render();
    }

    /**
     * Magic accessor for model autoloading.
     *
     * @param  string $name Property name
     * @return object The model instance
     */
    public function __get($name)
    {
        return $this->loadModel($name);
    }

    /**
     * load model
     * It assumes the model's constructor doesn't need parameters for constructor
     *
     * @param string  $model class name
     */
    public function loadModel($model)
    {
        $ucModel = ucwords($model);

        return $this->{$model} = new $ucModel();
    }

    /**
     * Insert default parameters to head.
     * 
     * @return $this
     */
    protected function defaultHeadParams()
    {
        // Add variables to site head.
        $this->view->addHead([
            'language'    => Handler::get('LANGUAGE'),
            'title'       => Handler::get('TITLE'),
            'description' => Handler::get('DESCRIPTION'),
            'robots'      => Handler::get('ROBOTS'),
            'keywords'    => Handler::get('KEYWORDS'),
        ]);
        return $this;
    }

    /**
     * Insert default parameters to content.
     * 
     * @return $this
     */
    protected function defaultContentParams()
    {
        // Add variables to site head.
        $this->view->addParams([
            'static' => Handler::get('STATIC'),
            'path'   => Handler::get('PATH'),
        ]);
        return $this;
    }
}
