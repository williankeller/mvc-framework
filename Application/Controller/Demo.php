<?php

namespace Application\Controller;

use Application\Core\Controller;
use Application\Core\Handler;

class Demo extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function beforeAction()
    {
        parent::beforeAction();

        // Pass data to script.
        Handler::setScriptData('page', 'error');
    }

    public function index()
    {
        $this->view->render();
    }
}
