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

class InstallModel {

  public function __construct() {
    
  }

  public function connectDatabase() {

    // Get instance Router to object param
    if (isset($_POST['database'])) {

      $data = $_POST['database'];

      $this->host = $data['host'];
      $this->db_name = $data['name'];
      $this->password = $data['pass'];
      $this->user = $data['user'];

      $pdo_details = "mysql:host={$this->host};";
      $pdo_details .= "dbname={$this->db_name};";
      $pdo_details .= "charset=utf8;";

      $_SESSION['database'] = $_POST['database'];

      try {

        $this->pdo = new PDO($pdo_details, $this->user, $this->password);

        $this->createConfigFile($data);

        Common::r2('install/settings');
      } catch (PDOException $e) {

        $_POST['database']['response'] = 'has-error';
      }

      return;
    }
  }

  public function createConfigFile($data) {

    $configFile = CORE_PATH . 'Config.php';

    $reading = fopen($configFile, 'r');
    $writing = fopen(CORE_PATH . 'Config.tmp', 'w');

    $replaced = false;

    while (!feof($reading)) {

      $line = fgets($reading);

      if (stristr($line, "define('DB_HOST', '');")) {
        $line = "define('DB_HOST', '{$data['host']}');\n";
        $replaced = true;
      }

      if (stristr($line, "define('DB_NAME', '');")) {
        $line = "define('DB_NAME', '{$data['name']}');\n";
        $replaced = true;
      }

      if (stristr($line, "define('DB_USER', '');")) {
        $line = "define('DB_USER', '{$data['user']}');\n";
        $replaced = true;
      }

      if (stristr($line, "define('DB_PASS', '');")) {
        $line = "define('DB_PASS', '{$data['pass']}');\n";
        $replaced = true;
      }

      fputs($writing, $line);
    }

    fclose($reading);
    fclose($writing);

    // might as well not overwrite the file if we didn't replace anything
    if ($replaced) {
      rename(CORE_PATH . 'Config.tmp', $configFile);
    } else {
      unlink(CORE_PATH . 'Config.tmp');
    }
  }

  public function siteSettings() {

    // Get instance Router to object param
    if (isset($_POST['database'])) {

      $data = $_POST['database'];

      $this->host = $data['host'];
      $this->db_name = $data['name'];
      $this->password = $data['pass'];
      $this->user = $data['user'];

      $pdo_details = "mysql:host={$this->host};";
      $pdo_details .= "dbname={$this->db_name};";
      $pdo_details .= "charset=utf8;";

      $_SESSION['database'] = $_POST['database'];

      try {

        $this->pdo = new PDO($pdo_details, $this->user, $this->password);

        Common::r2('install/settings');
      } catch (PDOException $e) {

        $_POST['database']['response'] = 'has-error';
      }

      return;
    }
  }

  public function baseURL() {

    return URL;
  }

  public function databaseUsers() {

    // Get instance Router to object param
    if (isset($_POST['user']['post'])) {

      $data = $_POST['user'];

      $this->db = new Database();

      $this->db->query("CREATE TABLE IF NOT EXISTS `lmvc_users` (
        `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Primary Key: Unique user ID.',
        `user` varchar(254) DEFAULT '' COMMENT 'Username used for initial account creation.',
        `email` varchar(254) DEFAULT '' COMMENT 'E-mail address used for initial account creation.', 
        `name` varchar(60) NOT NULL DEFAULT '' COMMENT 'Unique user name.',
        `pass` varchar(128) NOT NULL DEFAULT '' COMMENT 'Users password (hashed).',
        `created` int(11) NOT NULL DEFAULT '0' COMMENT 'Timestamp for when user was created.',
        `access` int(11) DEFAULT NULL COMMENT 'Timestamp for previous time user accessed the site.',
        `login` int(11) DEFAULT NULL COMMENT 'Timestamp for users last login.',
        `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Whether the user is active(1) or blocked(0).',
        `lang` varchar(5) NOT NULL DEFAULT '' COMMENT 'Users default language.',
        `picture` int(11) DEFAULT NULL COMMENT 'Users picture.',

        PRIMARY KEY (`uid`),
        KEY `user` (`user`),
        KEY `access` (`access`),
        KEY `created` (`created`),
        KEY `email` (`email`),
        KEY `picture` (`picture`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='System user data.';");

      $pass = md5($data['pass']);
      $time = time();

      $this->db->query("INSERT INTO `lmvc_users` (`uid`, `user`, `email`, `name`, `pass`, `created`, `status`, `lang`) VALUES
      (1, '{$data['user']}', '{$data['email']}', '{$data['name']}', '{$pass}', '{$time}', '1', 'en-us')");
      
      $this->removeInstallation();
      
      Common::r2('');
    }
  }

  public function databaseSettings() {

    // Get instance Router to object param
    if (isset($_POST['site']['post'])) {

      $data = $_POST['site'];

      $this->db = new Database();

      $this->db->query("CREATE TABLE IF NOT EXISTS `lmvc_settings` (
        `sid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Primary Key: Unique site ID.',
        `site_name` varchar(64) DEFAULT '' COMMENT 'Website Default title.',
        `site_url` varchar(256) DEFAULT '' COMMENT 'Website Default URL.', 
        `site_desc` varchar(128) NOT NULL DEFAULT '' COMMENT 'Website Default description.',
        `default_cache` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'System cached active(1) or disabled(0).',
        `default_lang` varchar(5) NOT NULL DEFAULT '' COMMENT 'System default language.',
        `data` int(11) NOT NULL DEFAULT '0' COMMENT 'Extra Website Data.',

        PRIMARY KEY (`sid`),
        KEY `site_name` (`site_name`),
        KEY `site_url` (`site_url`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='System website data.';");

      $this->db->query("INSERT INTO `lmvc_settings` (`sid`, `site_name`, `site_url`, `site_desc`, `default_cache`, `default_lang`) VALUES
      (1, '{$data['name']}', '{$data['url']}', '{$data['desc']}', '{$data['cache']}', 'en-us')");
      
      Common::r2('install/user');
    }
  }

  public function removeInstallation() {

    unlink(SYS_PATH . 'controllers' . DIRECTORY_SEPARATOR . 'InstallController.php');
    unlink(SYS_PATH . 'models' . DIRECTORY_SEPARATOR . 'InstallModel.php');

    array_map('unlink', glob(SYS_PATH . 'views' . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . '*.phtml'));
    
    rmdir(SYS_PATH . 'views' . DIRECTORY_SEPARATOR . 'install');
  }

}
