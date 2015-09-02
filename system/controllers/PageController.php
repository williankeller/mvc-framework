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
        
        $content = $this->view->factory('page/index', array(
            '_model' => $this->model->load('pageModel')
        ));

        $data = array(
            '_title' => 'Index page to Parameter',
            '_content' => $content
        );

        $this->view->render($data);
    }

    /**
     * To page edit action.
     */
    public function editAction() {

        $content = $this->view->factory('page/edit', array(
            '_cache' => false,
            '_model' => $this->model->load('pageModel')
        ));

        $data = array(
            '_title' => 'Page with Parameter',
            '_content' => $content
        );

        $this->view->render($data);
    }

}
