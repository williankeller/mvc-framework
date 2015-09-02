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

abstract class Controller {

    /**
     * Instance of View class. This is required for rendering
     * @var View
     */
    protected $view;

    /**
     * Instance of Model class. This is required for model contents
     * @var Model
     */
    protected $model;
    
    /**
     * Instance of Database class. This is required for model contents
     * @var Database
     */
    #protected $db;

    /**
     * Constructor.
     */
    public function __construct() {

        $this->view = new View();

        $this->model = new Model();

        #$this->db = new Database();
    }

    /**
     * To force default index action in all controllers.
     */
    public abstract function indexAction();
}
