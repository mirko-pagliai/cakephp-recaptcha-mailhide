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
namespace RecaptchaMailhide\Utility;

use Cake\Core\Configure;
use Cake\Utility\Security as CakeSecurity;

/**
 * Security
 */
class Security extends CakeSecurity
{
    /**
     * Decrypts and decodes an email address
     * @param string $mail Email address
     * @param string $key The 256 bit/32 byte key to use as a cipher key. Leave
     *  `null` to use `Security.salt`
     * @param string|null $hmacSalt The salt to use for the HMAC process. Leave
     *  `null` to use `Security.salt`
     * @return string Decrypted email address
     */
    public static function decryptMail($mail, $key = null, $hmacSalt = null)
    {
        $key = $key ?: Configure::read('RecaptchaMailhide.encryptKey');

        return parent::decrypt(base64_decode($mail), $key, $hmacSalt);
    }

    /**
     * Encrypts and encodes an email address
     * @param string $mail Email address
     * @param string $key The 256 bit/32 byte key to use as a cipher key. Leave
     *  `null` to use `Security.salt`
     * @param string|null $hmacSalt The salt to use for the HMAC process. Leave
     *  `null` to use `Security.salt`
     * @return string Encrypted email address
     */
    public static function encryptMail($mail, $key = null, $hmacSalt = null)
    {
        $key = $key ?: Configure::read('RecaptchaMailhide.encryptKey');

        return base64_encode(parent::encrypt($mail, $key, $hmacSalt));
    }
}
