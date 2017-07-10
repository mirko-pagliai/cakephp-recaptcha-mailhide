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
namespace RecaptchaMailhide\Utility;

use Cake\Core\Configure;
use Cake\Utility\Security as CakeSecurity;

/**
 * Security
 */
class Security extends CakeSecurity
{
    /**
     * Decrypts an email address
     * @param string $mail Email address
     * @param string $key The 256 bit/32 byte key to use as a cipher key. Leave
     *  `null` to use `Security.salt`
     * @param string|null $hmacSalt The salt to use for the HMAC process. Leave
     *  `null` to use `Security.salt`
     * @return string Decrypted email address
     */
    public static function decryptMail($mail, $key = null, $hmacSalt = null)
    {
        if (empty($key)) {
            $key = Configure::read(RECAPTCHA_MAILHIDE . '.encryptKey');
        }

        return parent::decrypt(rawurldecode($mail), $key, $hmacSalt);
    }

    /**
     * Encrypts an email address
     * @param string $mail Email address
     * @param string $key The 256 bit/32 byte key to use as a cipher key. Leave
     *  `null` to use `Security.salt`
     * @param string|null $hmacSalt The salt to use for the HMAC process. Leave
     *  `null` to use `Security.salt`
     * @return string Encrypted email address
     */
    public static function encryptMail($mail, $key = null, $hmacSalt = null)
    {
        if (empty($key)) {
            $key = Configure::read(RECAPTCHA_MAILHIDE . '.encryptKey');
        }

        return rawurlencode(parent::encrypt($mail, $key, $hmacSalt));
    }
}
