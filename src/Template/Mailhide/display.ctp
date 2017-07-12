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
