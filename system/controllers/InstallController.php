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

class InstallController extends Controller {

  public function indexAction() {

    $_model = $this->model->load();

    $content = $this->view->factory('install/index', array(
        '_model' => $_model,
        '_translate' => $this->translate
    ));

    $data = array(
        '_title' => $this->translate->__('Database connection'),
        '_content' => $content,
        '_translate' => $this->translate
    );

    $this->view->render($data);
  }
  
  public function settingsAction() {

    $_model = $this->model->load();

    $content = $this->view->factory('install/site', array(
        '_model' => $_model,
        '_translate' => $this->translate
    ));

    $data = array(
        '_title' => $this->translate->__('Your site settings'),
        '_content' => $content,
        '_translate' => $this->translate
    );

    $this->view->render($data);
  }
  
  public function userAction() {

    $_model = $this->model->load();

    $content = $this->view->factory('install/user', array(
        '_model' => $_model,
        '_translate' => $this->translate
    ));

    $data = array(
        '_title' => $this->translate->__('Your access account'),
        '_content' => $content,
        '_translate' => $this->translate
    );

    $this->view->render($data);
  }

}
