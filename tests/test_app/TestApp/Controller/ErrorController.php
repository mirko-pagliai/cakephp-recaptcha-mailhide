<?php
namespace App\Controller;

use Cake\Event\Event;

class ErrorController extends AppController
{
    public function initialize()
    {
        $this->loadComponent('RequestHandler');
    }

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);

        $this->viewBuilder()->layout('error');
        $this->viewBuilder()->templatePath('Error');
    }
}
