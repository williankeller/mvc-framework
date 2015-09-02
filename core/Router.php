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
     * This array defines url configuration
     * Complete pattern looks like '/module/controller/action/id/name/seo'
     * Comment out in the array the components which are not requierd
     * @var array URL segments
     */
    private static $urlConfig = array(
        'controller',
        'action',
        'param'
    );

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

        if (isset($_GET['url'])) {
            
            self::setUri($_GET['url']);

            $uris = explode('/', trim(self::$_uri, '/'));
            
            self::setController($uris[0]);
            
            if (isset($uris[1])) {

                self::setAction($uris[1]);
            }
            
            if (isset($uris[2])){
                
                unset($uris[0]);
                unset($uris[1]);
                
                self::setParam(array_values($uris));
            }
        }

        $controllerClass = self::getController();
        
        if (!class_exists($controllerClass)) {
            
            throw new Exception('Could not find class ' . $controllerClass);
        }

        $controller = new $controllerClass;

        $method = self::getAction() . 'Action';

        if (!method_exists($controller, $method)) {

            throw new Exception(get_class($controller) . " class does not have a method '$method'. Exiting...");
        }

        $controller->$method();
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
        
        self::$_controller = $controller . 'controller';
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
