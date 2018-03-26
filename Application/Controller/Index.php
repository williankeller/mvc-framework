<?php

namespace Application\Controller;

use Application\Core\Controller;
use Application\Core\Handler;

class Index extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function beforeAction()
    {
        parent::beforeAction();

        // Pass data to script.
        Handler::setScript('page', 'home');
    }
}
