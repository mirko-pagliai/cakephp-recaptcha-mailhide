<?php
/**
 * This file is part of cakephp-recaptcha-mailhide.
 *
 * cakephp-recaptcha-mailhide is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * cakephp-recaptcha-mailhide is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with cakephp-recaptcha-mailhide.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author      Mirko Pagliai <mirko.pagliai@gmail.com>
 * @copyright   Copyright (c) 2017, Mirko Pagliai for Nova Atlantis Ltd
 * @license     http://www.gnu.org/licenses/agpl.txt AGPL License
 * @link        https://github.com/mirko-pagliai/cakephp-recaptcha-mailhide
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
        <div class="container-fluid">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>
    </body>
</html>