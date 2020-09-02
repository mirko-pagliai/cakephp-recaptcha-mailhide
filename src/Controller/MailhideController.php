<?php
declare(strict_types=1);

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
use Tools\Exceptionist;

/**
 * MailhideController
 */
class MailhideController extends AppController
{
    /**
     * Display action
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException
     * @throws \Cake\Controller\Exception\MissingComponentException
     * @uses \RecaptchaMailhide\Utility\Security::decryptMail()
     */
    public function display(): void
    {
        $hasRecaptcha = $this->components()->has('Recaptcha');
        Exceptionist::isTrue($hasRecaptcha, __d('recaptcha_mailhide', 'Missing {0} component', 'Recaptcha'), MissingComponentException::class);

        $mail = $this->getRequest()->getQuery('mail');
        Exceptionist::isTrue($mail, __d('recaptcha_mailhide', 'Missing mail value'), BadRequestException::class);

        if ($this->getRequest()->is('post') && $this->Recaptcha->verify()) {
            $mail = Security::decryptMail($mail);
            Exceptionist::isTrue($mail, __d('recaptcha_mailhide', 'Invalid mail value'), BadRequestException::class);
            $this->set(compact('mail'));
        }

        $this->viewBuilder()->setLayout('RecaptchaMailhide.default');
    }
}
