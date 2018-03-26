<?php

/**
 * Error controller class.
 *
 * Copyright (C) 2018 MVC Framework.
 * This file included in MVC Framework is licensed under OSL 3.0
 *
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace Application\Controller;

use Application\Core\Controller;

class Errors extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function beforeAction()
    {
        parent::beforeAction();
    }

    /**
     * Error404 method action.
     * This action will call view/Content/Errors/error404 file.
     *
     * @access public
     */
    public function error404()
    {
        $this->view->render();
    }
}
