<?php

namespace Application\Controller;

use Application\Core\Controller;
use Application\Core\Handler;

class Errors extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function beforeAction()
    {
        parent::beforeAction();
    }

    public function page404()
    {
        $this->view->render();
    }

}
