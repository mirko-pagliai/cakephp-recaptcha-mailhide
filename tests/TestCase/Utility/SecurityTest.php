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
namespace RecaptchaMailhide\Test\TestCase\Utility;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use RecaptchaMailhide\Utility\Security;

class SecurityTest extends TestCase
{
    /**
     * Test for `decryptMail()` method
     * @test
     */
    public function testDecryptMail()
    {
        $key = Configure::read(RECAPTCHA_MAILHIDE . '.encryptKey') . '01234';
        $hmacSalt = Configure::read('Security.salt') . '01234';

        foreach (['first@email.com', 'second@provider.com', 'example@myexample.com'] as $mail) {
            $encrypted = Security::encryptMail($mail);
            $decrypted = Security::decryptMail($encrypted);
            $this->assertEquals($decrypted, $mail);

            $encrypted = Security::encryptMail($mail, $key);
            $decrypted = Security::decryptMail($encrypted, $key);
            $this->assertEquals($decrypted, $mail);

            $encrypted = Security::encryptMail($mail, $key, $hmacSalt);
            $decrypted = Security::decryptMail($encrypted, $key, $hmacSalt);
            $this->assertEquals($decrypted, $mail);
        }
    }

    /**
     * Test for `encryptMail()` method
     * @test
     */
    public function testEncryptMail()
    {
        $key = Configure::read(RECAPTCHA_MAILHIDE . '.encryptKey') . '01234';
        $hmacSalt = Configure::read('Security.salt') . '01234';

        foreach (['first@email.com', 'second@provider.com', 'example@myexample.com'] as $mail) {
            $this->assertNotEmpty(Security::encryptMail($mail));
            $this->assertNotEmpty(Security::encryptMail($mail, $key));
            $this->assertNotEmpty(Security::encryptMail($mail, $hmacSalt));
        }
    }
}
