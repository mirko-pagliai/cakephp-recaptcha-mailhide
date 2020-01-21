<?php
declare(strict_types=1);

use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;

Router::scope('/', function (RouteBuilder $routes) {
    $routes->loadPlugin('RecaptchaMailhide');
});
