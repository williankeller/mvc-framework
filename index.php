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

header_remove('X-Powered-By'); // PHP 5.3+

/*
 * Inicia a saída buffer
 */
ini_set('expose_php', 'off');
ini_set('session.cookie_httponly', 1);
ini_set('post_max_size', 50);
ini_set('memory_limit', '256M');
ini_set('max_execution_time', 180);

/*
 * session_name
 * Define nome dinâmico para a sessão
 */
session_name(md5("mvc_" . $_SERVER['REMOTE_ADDR']));

/*
 * session_set_cookie_params
 * Define periodo da sessão
 */
session_set_cookie_params(2 * 7 * 24 * 60 * 60);

/*
 * Define global app path
 */
define("APP_PATH", dirname(__FILE__) . DIRECTORY_SEPARATOR);

/*
 * File required to include all configs
 */
require_once( APP_PATH . 'core' . DIRECTORY_SEPARATOR . 'Config.php');
