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
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?= $this->fetch('title') ?></title>
        <?php
            echo $this->Html->charset();
            echo $this->Html->meta([
                'name' => 'viewport',
                'content' => 'width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no',
            ]);
            echo $this->fetch('meta');
            echo $this->Html->css('//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
            echo $this->Html->css(RECAPTCHA_MAILHIDE . '.default.css');
            echo $this->fetch('css');
            echo $this->fetch('script');
        ?>
    </head>
    <body>
        <div class="container-fluid text-center">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>
    </body>
</html>