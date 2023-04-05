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
use Recaptcha\Controller\Component\RecaptchaComponent;
use RecaptchaMailhide\Utility\Security;
use Tools\Exceptionist;

/**
 * MailhideController
 */
class MailhideController extends AppController
{
    /**
     * @var \Recaptcha\Controller\Component\RecaptchaComponent
     */
    public RecaptchaComponent $Recaptcha;

    /**
     * Display action
     * @return void
     * @throws \ErrorException
     */
    public function display(): void
    {
        Exceptionist::isTrue($this->components()->has('Recaptcha'), __d('recaptcha_mailhide', 'Missing {0} component', 'Recaptcha'));

        $mail = $this->getRequest()->getQuery('mail');
        Exceptionist::isTrue($mail && is_string($mail), __d('recaptcha_mailhide', 'Missing mail value'));

        /** @var string $mail */
        if ($this->getRequest()->is('post') && $this->Recaptcha->verify()) {
            $mail = Security::decryptMail($mail);
            Exceptionist::isTrue($mail, __d('recaptcha_mailhide', 'Invalid mail value'));
            $this->set(compact('mail'));
        }

        $this->viewBuilder()->setLayout('RecaptchaMailhide.default');
    }
}
