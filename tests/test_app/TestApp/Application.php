<?php
declare(strict_types=1);

namespace App;

use Cake\Http\BaseApplication;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\Middleware\RoutingMiddleware;
use RecaptchaMailhide\Plugin as RecaptchaMailhide;

class Application extends BaseApplication
{
    public function bootstrap(): void
    {
        $this->addPlugin(RecaptchaMailhide::class);
    }

    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        return $middlewareQueue->add(new RoutingMiddleware($this));
    }
}
