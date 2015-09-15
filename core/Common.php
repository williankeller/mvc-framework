<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Common
 *
 * @author wkeller
 */
class Common {

  //put your code here

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

}
