<?php

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

    public function errore404()
    {
        $this->view->render();
    }

}
