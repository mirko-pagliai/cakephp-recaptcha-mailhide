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
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\InternalErrorException;
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
     * @throws InternalErrorException
     * @uses \RecaptchaMailhide\Utility\Security::decryptMail()
     */
    public function display()
    {
        if (!$this->components()->has('Recaptcha')) {
            throw new InternalErrorException(__d('recaptcha-mailhide', 'Missing {0} component', 'Recaptcha'));
        }

        $mail = $this->request->getQuery('mail');

        if (!$mail) {
            throw new BadRequestException(__d('recaptcha-mailhide', 'Missing mail value'));
        }

        if ($this->request->is('post') && $this->Recaptcha->verify()) {
            $mail = Security::decryptMail($mail);

            if (!$mail) {
                throw new BadRequestException(__d('recaptcha-mailhide', 'Invalid mail value'));
            }

            $this->set(compact('mail'));
        }

        $this->viewBuilder()->setLayout(RECAPTCHA_MAILHIDE . '.default');
    }
}
