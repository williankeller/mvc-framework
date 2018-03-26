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
        Handler::setScript('page', 'demo');
    }

    /**
     * Test method action.
     * This action will call view/Content/Demo/test file.
     *
     * @access public
     */
    public function test()
    {
        $this->view->addHead([
            'title' => 'A',
            'description' => 'B',
        ]);
        $this->view->render();
    }
}
