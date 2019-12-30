<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Controller\Controller;

class AppController extends Controller
{
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Recaptcha.Recaptcha', [
            'enable' => true,
            'sitekey' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
            'secret' => 'bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb',
            'type' => 'image',
            'theme' => 'light',
            'lang' => 'en',
            'size' => 'normal',
        ]);
    }
}
