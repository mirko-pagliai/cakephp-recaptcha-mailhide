<?php
/**
 * This file is part of cakephp-recaptcha-mailhide.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright   Copyright (c) Mirko Pagliai
 * @link        https://github.com/mirko-pagliai/cakephp-recaptcha-mailhide
 * @license     https://opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::plugin(RECAPTCHA_MAILHIDE, ['path' => '/'], function ($routes) {
    $routes->connect(
        '/mailhide',
        ['controller' => 'Mailhide', 'action' => 'display'],
        ['_name' => 'mailhide']
    );

    $routes->fallbacks(DashedRoute::class);
});
