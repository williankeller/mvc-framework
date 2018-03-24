<?php

/**
 * The view class.
 * Responsible for rendering files as HTML, encode JSON, with some helper methods
 *
 * Copyright (C) 2018 MVC Framework.
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace Core;

class View
{

    /**
     * controller object that instantiated view object
     *
     * @var object
     */
    public $controller;

    /**
     * Constructor
     *
     * @param Controller $controller
     */
    public function __construct(
        Controller $controller
    ) {
        $this->controller = $controller;
    }

    /**
     * Renders and returns output for the given file with its array of data.
     *
     * @param  string  $filePath
     * @param  array   $data
     * @return string  Rendered output
     *
     */
    public function render($filePath, $data = null)
    {
        if (!empty($data)) {
            extract($data);
        }

        // Using include Vs require is better,
        // because we may want to include a file more than once.
        ob_start();
        include $filePath . '';
        $renderedFile = ob_get_clean();

        $this->controller->response->setContent($renderedFile);
        return $renderedFile;
    }

    /**
     * Renders and returns output with header and footer for the given file with its array of data.
     *
     * @param  string  $layoutDir
     * @param  string  $filePath
     * @param  array   $data
     * @return string  Rendered output
     */
    public function renderWithLayouts($layoutDir, $filePath, $data = null)
    {
        if (!empty($data)) {
            extract($data);
        }

        ob_start();
        require_once $layoutDir . "header.php";
        require_once $filePath . "";
        require_once $layoutDir . "footer.php";
        $renderedFile = ob_get_clean();

        $this->controller->response->setContent($renderedFile);
        return $renderedFile;
    }

    /**
     * Render a JSON view.
     *
     * @param  array   $data
     * @return string  Rendered output
     */
    public function renderJson($data)
    {
        $jsonData = $this->jsonEncode($data);

        $this->controller->response->type('application/json')->setContent($jsonData);
        return $jsonData;
    }

    /**
     * Renders errors
     * A json respond will be sent in case of ajax call
     *
     * @param  array  $errors
     * @return mixed  Rendered output
     */
    public function renderErrors($errors)
    {
        $html = $this->render(Config::get('VIEWS_PATH') . 'alerts/errors.php', ["errors" => $errors]);

        if ($this->controller->request->isAjax()) {
            return $this->renderJson(array("error" => $html));
        }
        else {
            $this->controller->response->setContent($html);
            return $html;
        }
    }

    /**
     * Renders success message
     * A json respond will be sent in case of ajax call
     *
     * @param  string  $message
     * @return mixed  Rendered output
     */
    public function renderSuccess($message)
    {
        $html = $this->render(Config::get('VIEWS_PATH') . 'alerts/success.php', array("success" => $message));

        if ($this->controller->request->isAjax()) {
            return $this->renderJson(array("success" => $html));
        }
        else {
            $this->controller->response->setContent($html);
            return $html;
        }
    }

    /**
     * Serialize array to JSON and used for the response
     *
     * @param  array   $data
     * @return string  Rendered output
     *
     */
    public function jsonEncode($data)
    {
        return json_encode($data);
    }

    /**
     * Cuts a string to the length of $length and replaces the last characters
     * with the ellipsis => '...' if the text is longer than length.
     *
     * @param  string $str
     * @param  string $len
     * @return string the truncated string
     */
    public function truncate($str, $len)
    {
        if (empty($str)) {
            return "";
        }
        else if (mb_strlen($str, 'UTF-8') > $len) {
            return mb_substr($str, 0, $len, "UTF-8") . " ...";
        }
        else {
            return mb_substr($str, 0, $len, "UTF-8");
        }
    }

    /**
     * formats timestamp string coming from the database.
     *
     * @param  string  $timestamp MySQL TIMESTAMP
     * @return string  Date after formatting.
     */
    public function timestamp($timestamp, $format = "F j, Y")
    {
        $unixTime = strtotime($timestamp);

        $date = date($format, $unixTime);

        // What if date() failed to format? It will return false.
        return (empty($date)) ? "" : $date;
    }

    /**
     * Converts characters to HTML entities
     * This is important to avoid XSS attacks, and attempts to inject malicious code in your page.
     *
     * @param  string $str The string.
     * @return string
     */
    public function encodeHTML($str)
    {
        return htmlentities($str, ENT_QUOTES, 'UTF-8');
    }

    /**
     * It's same as encodeHTML(), But, also use nl2br() function in PHP
     *
     * @param  string	The string.
     * @return string	The string after converting characters and inserting br tags.
     */
    public function encodeHTMLWithBR($str)
    {
        return nl2br(htmlentities($str, ENT_QUOTES, 'UTF-8'));
    }

}
