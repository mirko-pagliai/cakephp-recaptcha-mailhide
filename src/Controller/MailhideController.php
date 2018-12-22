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
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use RecaptchaMailhide\Utility\Security;

/**
 * MailhideController
 */
class MailhideController extends AppController
{
    /**
     * Display action
     * @return void
     * @throws BadRequestException
     * @throws MissingComponentException
     * @uses \RecaptchaMailhide\Utility\Security::decryptMail()
     */
    public function display()
    {
        is_true_or_fail($this->components()->has('Recaptcha'), __d('recaptcha-mailhide', 'Missing {0} component', 'Recaptcha'), InternalErrorException::class);

        $mail = $this->request->getQuery('mail');
        is_true_or_fail($mail, __d('recaptcha-mailhide', 'Missing mail value'), BadRequestException::class);

        if ($this->request->is('post') && $this->Recaptcha->verify()) {
            $mail = Security::decryptMail($mail);
            is_true_or_fail($mail, __d('recaptcha-mailhide', 'Invalid mail value'), BadRequestException::class);
            $this->set(compact('mail'));
        }

        $this->viewBuilder()->setLayout('RecaptchaMailhide.default');
    }
}
