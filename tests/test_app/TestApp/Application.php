<?php
namespace App;

use Cake\Http\BaseApplication;
use Cake\Routing\Middleware\RoutingMiddleware;
use RecaptchaMailhide\Plugin as RecaptchaMailhide;

class Application extends BaseApplication
{
    public function bootstrap()
    {
        $this->addPlugin(RecaptchaMailhide::class, ['routes' => false]);
    }

    public function middleware($middlewareQueue)
    {
        return $middlewareQueue->add(new RoutingMiddleware($this));
    }
}
