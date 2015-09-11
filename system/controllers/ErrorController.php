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

class ErrorController extends Controller {

    /**
     * Default action.
     */
    public function indexAction() {
        
    }

    /*
     * Function to get lang Action
     */
    public function error404Action() {

        /*
         * Factory file on /views/error/404.phtml
         */
        $content = $this->view->factory('error/404', array(
            '_translate' => $this->translate
        ));

        $data = array(
            /*
             * Defined in Page Meta Title
             * @use $_title 
             */
            '_title' => 'Page not Found | Error 404',
            /*
             * Defined in Page Meta Description
             * @use $_description
             */
            '_description' => 'Sorry 404! Page not found',
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
