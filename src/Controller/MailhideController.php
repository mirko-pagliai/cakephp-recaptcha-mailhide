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
namespace RecaptchaMailhide\Controller;

use App\Controller\AppController;
use Cake\Controller\Exception\MissingComponentException;
use Cake\Http\Exception\BadRequestException;
use RecaptchaMailhide\Utility\Security;

/**
 * MailhideController
 */
class MailhideController extends AppController
{
    /**
     * @var \Recaptcha\Controller\Component\RecaptchaComponent
     */
    public $Recaptcha;

    /**
     * Display action
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException
     * @throws \Cake\Controller\Exception\MissingComponentException
     * @uses \RecaptchaMailhide\Utility\Security::decryptMail()
     */
    public function display()
    {
        is_true_or_fail($this->components()->has('Recaptcha'), __d('recaptcha_mailhide', 'Missing {0} component', 'Recaptcha'), MissingComponentException::class);

        $mail = $this->getRequest()->getQuery('mail');
        is_true_or_fail($mail, __d('recaptcha_mailhide', 'Missing mail value'), BadRequestException::class);

        if ($this->getRequest()->is('post') && $this->Recaptcha->verify()) {
            //@phpstan-ignore-next-line
            $mail = Security::decryptMail($mail);
            is_true_or_fail($mail, __d('recaptcha_mailhide', 'Invalid mail value'), BadRequestException::class);
            $this->set(compact('mail'));
        }

        $this->viewBuilder()->layout('RecaptchaMailhide.default');
    }
}
