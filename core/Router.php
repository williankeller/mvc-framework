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

class Router {

    /**
     * Instance to enforce singleton
     * @var unknown_type
     */
    private static $_instance = null;

    /**
     * URI components
     * @var string
     */
    static private $_controller = null;
    static private $_action = null;
    static private $_id = null;

    /**
     * The complete URI as is
     * @var string
     */
    private static $_uri = null;

    /**
     * Constructor.
     */
    private function __construct() {
        
    }

    /**
     * All routing related code is here. Seperates controller, action and arguments and
     * invoke the action on the controller with arguments.
     * 
     * @throws Exception
     */
    private function _route() {

        $this->setController('Index');
        $this->setAction('index');

        if (filter_input(INPUT_GET, 'url')) {

            /*
             * Isn't safe to access super global variables without a filter input.
             */
            $url = explode('/', filter_input(INPUT_GET, 'url'));

            self::setUri($url);

            $value = $this->shiftURL($url);

            if (!empty($value['controller'])) {

                self::setController($value['controller']);
            }
            if (!empty($value['action'])) {

                self::setAction($value['action']);
            }

            self::setParam($value['param']);
        }

        $controllerName = self::getController() . 'Controller';
        $actionName = self::getAction() . 'Action';

        $controllerClass = new $controllerName();

        if (!method_exists($controllerClass, $actionName)) {

            throw new Exception(sprintf("[%s] class does not have a method called [%s].", $controllerName, $actionName));
        }

        $controllerClass->$actionName();
    }

    private function shiftURL($url) {

        $value = array();

        $value['controller'] = array_shift($url);
        $value['action'] = array_shift($url);
        $value['param'] = array_values($url);

        return $value;
    }

    /**
     * Does the initializations for routing and forces singleton.
     * Prevents constructor and route methods being called directly
     * @return instance of Router class
     */
    public static function init() {

        if (is_null(self::$_instance)) {

            self::$_instance = new Router();
        }

        self::$_instance->_route();
    }

    /**
     * Getter for module.
     */
    static function getModule() {

        return self::$_module;
    }

    /**
     * Getter for controller.
     */
    static function getController() {

        return self::$_controller;
    }

    /**
     * Getter for action.
     */
    static function getAction() {

        return self::$_action;
    }

    /**
     * Getter for id.
     */
    static function getParam($slice = 0) {

        return self::$_id[$slice];
    }

    /**
     * Getter for uri.
     */
    static function getUri() {

        return self::$_uri;
    }

    /**
     * Setter for controller.
     * @param string $controller
     */
    private function setController($controller) {

        self::$_controller = $controller;
    }

    /**
     * Setter for action.
     * @param string $action
     */
    private function setAction($action) {

        self::$_action = $action;
    }

    /**
     * Setter for id.
     * @param string $id
     */
    private function setParam($id) {

        self::$_id = $id;
    }

    /**
     * Setter for uri.
     * @param string $uri
     */
    final private function setUri($uri) {

        self::$_uri = $uri;
    }

}
