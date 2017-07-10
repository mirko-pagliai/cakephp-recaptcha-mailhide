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

<div id="recaptcha-form">
    <?php if (empty($mail)) : ?>
        <p class="small">
            <?= __d('recaptcha-mailhide', 'Fill out the reCAPTCHA form to view the e-mail address') ?>
        </p>

        <?php
            echo $this->Form->create();
            echo $this->Recaptcha->display();
            echo $this->Form->submit(__d('recaptcha-mailhide', 'Show me the email address'));
            echo $this->Form->end();
        ?>
    <?php else : ?>
        <p><?= __d('recaptcha-mailhide', 'The email address you were looking for is:') ?></p>
        <p><?= $this->Html->link($mail, sprintf('mailto:%s', $mail)) ?></p>
    <?php endif; ?>
</div>
