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
        if (!in_array('Recaptcha', $this->components()->loaded())) {
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
