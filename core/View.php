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

class View {

    /**
     * Holds all the variables to be substitued in the layout file until the render
     * method is called.
     * @var array
     */
    private $_layoutData = array();

    /**
     * The default layout file. It can be overriden.
     * @var string
     */
    private $_layout = 'layout.phtml';

    /**
     * The template directory. All the view files should be placed in this directory. 
     * @var string
     */
    private $_template = 'default';

    /**
     * Constructor, wchich sets the layout if passed as argument.
     * @param string $layout
     */
    public function __construct($layout = null) {

        if (!is_null($layout)) {

            $this->_layout = $layout;
        }
    }

    /**
     * Factory method to create sub-view objects, that is, part of the layout. This method
     * is used to create the variables defined in the layout file.
     *
     * @param string $file
     * @param string $data
     */
    public function factory($file = null, $data = null) {

        ob_start();

        if (!is_null($file)) {

            if (is_array($data)) {

                extract($data);
            }
            require_once ROOT_PATH . 'system/views/' . $file . '.phtml';
        } else {

            ob_end_clean();

            throw new Exception('View file not found: ' . $file);
        }

        return ob_get_clean();
    }

    /**
     * Render the layout with the availlable sub-views, if any.
     */
    public function render($layoutData = null) {
        
        if (is_array($layoutData)) {

            $this->_layoutData = array_merge($this->_layoutData, $layoutData);
        }
        if (is_array($this->_layoutData)) {

            extract($this->_layoutData);

            require_once ROOT_PATH . 'system/views/' . $this->_layout;
        } else {
            throw new Exception('Invalid type, expected array.');
        }
    }

    /**
     * Set template directory if exists.
     * 
     * @param string $template
     * @throws Exception
     */
    public function setTemplate($template) {

        $dir = ROOT_PATH . 'system/views/' . $template;

        if (file_exists($dir) && is_dir($dir)) {

            $this->_template = $template;
        } else {
            throw new Exception('Template directory does not exist.');
        }
    }

    /**
     * Keep all variables in the layout in an array, until render them.
     * 
     * @param array $data associative array of vars and their values
     * @throws Exception
     */
    public function setLayoutData($data) {

        if (!is_array($data)) {

            throw new Exception('Invalid type, expected array.');
        }
        foreach ($data as $key => $value) {

            $this->_layoutData[$key] = $value;
        }
    }

}
