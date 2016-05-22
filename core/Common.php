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

class Common {

  public static function addFile($data, $type) {

    $compress = new Compress();

    switch ($type) {
      case 'css':
        echo $compress->addStyleSheet($data);
        break;
      case 'js':
        echo $compress->addJavaScript($data);
        break;
    }
  }

  public static function r2($route = '') {

    if ($route !== 'referer') {
      header('Location: ' . URL . $route);
      exit;
    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
  }

  public static function hasSession() {

    if (isset($_SESSION) && $_SESSION !== null) {
      return true;
    }
    return false;
  }

  public static function installation() {

    $this->db = new Database();

    $this->db->query("SELECT * FROM");
  }

}
