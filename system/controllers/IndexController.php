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

class IndexController extends Controller {

    /**
     * Default action called index.
     */
    public function indexAction() {

        /*
         * Factory file on /views/home/index.phtml
         */
        $content = $this->view->factory('home/index', array(
            /*
             * Define _welcome variable
             * @use $_welcome
             */
            '_welcome' => "LittleMVC",
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
            '_title' => 'LittleMVC',
            /*
             * Defined in Page Meta Description
             * @use $_description
             */
            '_description' => 'Welcome! This is a little MVC.',
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
