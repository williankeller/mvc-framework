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
class Compress {

  public function addStyleSheet($data = array()) {

    $this->type = 'css';

    return $this->checkData($data);
  }

  public function addJavaScript($data = array()) {

    $this->type = 'js';

    return $this->checkData($data);
  }

  protected function checkData($data) {

    if (isset($data['ensemble'])) {

      return $this->filesEnsemble($data);
    } else if (isset($data['inherent'])) {

      return $this->filesInherent($data);
    } else {
      return $this->filesDefalt($data);
    }
  }

  private function filesEnsemble($data) {

    return $this->fileCallOut($this->fileWrite($data));
  }

  private function filesInherent($data) {

    $content = $this->arrayContent($data['includes']);

    if (isset($data['compress'])) {

      $content = $this->filesCompress($content);
    }

    return $this->fileCallOut($content, true);
  }

  private function filesDefalt($data) {

    foreach ($data['includes'] as $file) {

      $content[] = $this->fileCallOut($this->_folder($file, true));
    }

    return implode(PHP_EOL, $content);
  }

  private function filesCompress($data) {

    return $this->compressContent($data);
  }

  private function fileCallOut($content, $method = null) {

    if ($method === true) {

      switch ($this->type) {

        case 'css':
          return '<style>' . $content . '</style>';
        case 'js':
          return '<script>' . $content . '</script>';
        default :
          return $content;
      }
    }
    switch ($this->type) {

      case 'css':
        return '<link rel="stylesheet" type="text/css" href="' . $content . '"/>';
      case 'js':
        return '<script src="' . $content . '"></script>';
      default :
        return $content;
    }
  }

  private function fileRead($file) {

    $fopen = fopen($this->_folder($file), 'r');

    return fread($fopen, filesize($this->_folder($file)));
  }

  private function fileWrite($data) {

    $content = $this->arrayContent($data['includes']);

    $filename = 'assets/' . $this->type . '/min/' . md5(date('Y-m')) . '.' . $this->type;

    $myfile = fopen($this->_folder($filename), "w") or die("Unable to open file!");

    if (isset($data['compress'])) {

      fwrite($myfile, $this->filesCompress($content));
    } else {

      fwrite($myfile, $content);
    }
    fclose($myfile);

    return $this->_folder($filename, true);
  }

  private function arrayContent($files) {

    foreach ($files as $file) {

      $content[] = $this->fileRead($file);
    }

    return implode(PHP_EOL, $content);
  }

  private function _folder($arquivo, $method = null) {

    // Retorna a rota base do arquivo com a extenção
    return (($method != null) ? URL : APP_PATH) . $arquivo;
  }

  /**
   * _compressContent()
   * Função responsável por remover os espaços desnecessários entre os caracteres
   *
   * @function private
   * @return void
   */
  private function compressContent($content) {

    $this->content = $content;

    $this->content = $this->_removeComments($this->content);

    $this->content = $this->_removeAllSpaces($this->content);

    $this->content = $this->_removeCharSpaces($this->content);

    return $this->content;
  }

  private function _removeCharSpaces($content) {

    $this->content = $content;

    $this->content = str_replace(array(" {", "{ "), '{', $this->content);
    $this->content = str_replace(array(" }", "} "), '}', $this->content);
    $this->content = str_replace(array(' <', '< '), '<', $this->content);
    $this->content = str_replace(array(' >', '> '), '>', $this->content);
    $this->content = str_replace(array(' +', '+ '), '+', $this->content);
    $this->content = str_replace(array(' -', '- '), '-', $this->content);
    $this->content = str_replace(array(' ]', '] '), ']', $this->content);
    $this->content = str_replace(array(' [', '[ '), '[', $this->content);
    $this->content = str_replace(array(';}', '} '), '}', $this->content);
    $this->content = str_replace(array(' ;', '; '), ';', $this->content);
    $this->content = str_replace(array(' (', '( '), '(', $this->content);
    $this->content = str_replace(array(' )', ') '), ')', $this->content);
    $this->content = str_replace(array(' ,', ', '), ',', $this->content);
    $this->content = str_replace(array(' :', ': '), ':', $this->content);

    return $this->content;
  }

  private function _removeAllSpaces($content) {

    $this->content = $content;

    $this->content = str_replace(array("\t", "\n", "\r", '  ', '    ', '     '), '', $this->content);
    $this->content = str_replace(array(' =', '= '), '=', $this->content);
    $this->content = str_replace(array(' ==', '== '), '==', $this->content);
    $this->content = str_replace(array(' &&', '&& '), '&&', $this->content);
    $this->content = str_replace(array(' ||', '|| '), '||', $this->content);
    $this->content = str_replace(array(' !==', '!== '), '!==', $this->content);
    $this->content = str_replace(array(' ===', '=== '), '===', $this->content);

    return $this->content;
  }

  private function _removeComments($content) {

    $this->content = $content;

    $this->content = preg_replace('!/\*.*?\*/!s', '', $this->content);
    $this->content = preg_replace('/\n\s*\n/', "\n", $this->content);
    $this->content = preg_replace('/((?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:\/\/.*))/', '', $this->content);

    return $this->content;
  }

}
