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

class PageController extends Controller {

    /**
     * Default action.
     */
    public function indexAction() {

        /*
         * Factory file on /views/page/index.phtml
         */
        $content = $this->view->factory('page/index', array(
            /*
             * Load _model obj
             * If load() is null, get the current Controller as Model
             * @use $_model
             */
            '_model' => $this->model->load(),
            /*
             * Define _translate obj
             * @use $_translate
             */
            '_translate' => $this->translate
        ));

        $data = array(
            /*
             * Defined in Page Meta Title
             * @use $_title 
             */
            '_title' => $this->translate->__('Index page to Parameter'),
            /*
             * Defined in /views/layout.phtml 
             * @use $_content
             */
            '_content' => $content,
            /*
             * Define _translate obj
             * @use $_translate
             */
            '_translate' => $this->translate
        );
        /*
         * To render data content
         */
        $this->view->render($data);
    }

    /**
     * To page edit action.
     */
    public function editAction() {
        
        /*
         * Factory file on /views/page/edit.phtml
         */
        $content = $this->view->factory('page/edit', array(
            /*
             * Disable cache on this context
             * Remove this to cached
             */
            '_cache' => false,
            /*
             * Load _model obj
             * If load() is null, get the current Controller as Model
             * @use $_model
             */
            '_model' => $this->model->load(),
            /*
             * Define _translate obj
             * @use $_translate
             */
            '_translate' => $this->translate
        ));

        $data = array(
            /*
             * Defined in Page Meta Title
             * @use $_title 
             */
            '_title' => $this->translate->__('Page with Parameter'),
            /*
             * Defined in Page Meta Description
             * @use $_description
             */
            '_description' => 'This page have a parameter on URL',
            /*
             * Defined in /views/layout.phtml 
             * @use $_content
             */
            '_content' => $content,
            /*
             * Define _translate obj
             * @use $_translate
             */
            '_translate' => $this->translate
        );
        /*
         * To render data content
         */
        $this->view->render($data);
    }
    
    /**
     * To page edit action.
     */
    public function anotherPageAction() {
        
        /*
         * Factory file on /views/page/another.phtml
         */
        $content = $this->view->factory('page/another', array(
            /*
             * Disable cache on this context
             * Remove this to cached
             */
            '_cache' => false,
            /*
             * Load _model obj
             * If load() is null, get the current Controller as Model
             * @use $_model
             */
            '_model' => $this->model->load(),
            /*
             * Define _translate obj
             * @use $_translate
             */
            '_translate' => $this->translate
        ));

        $data = array(
            /*
             * Defined in Page Meta Title
             * @use $_title 
             */
            '_title' => $this->translate->__('Another page with hyphen'),
            /*
             * Defined in Page Meta Description
             * @use $_description
             */
            '_description' => 'This page have a hyphen in URL',
            /*
             * Defined in /views/layout.phtml 
             * @use $_content
             */
            '_content' => $content,
            /*
             * Define _translate obj
             * @use $_translate
             */
            '_translate' => $this->translate
        );
        /*
         * To render data content
         */
        $this->view->render($data);
    }

}
