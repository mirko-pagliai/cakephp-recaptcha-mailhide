<?php
namespace App\Controller;

use Cake\Event\EventInterface;

class ErrorController extends AppController
{
    public function initialize()
    {
        $this->loadComponent('RequestHandler');
    }

    public function beforeRender(EventInterface $event)
    {
        parent::beforeRender($event);

        $this->viewBuilder()->layout('error');
        $this->viewBuilder()->templatePath('Error');
    }
}
