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
use MeTools\TestSuite\TestCase;
use RecaptchaMailhide\Utility\Security;

/**
 * SecurityTest class
 */
class SecurityTest extends TestCase
{
    /**
     * Test for `decryptMail()` method
     * @test
     */
    public function testDecryptMail()
    {
        $key = Configure::read('RecaptchaMailhide.encryptKey') . '01234';
        $hmacSalt = Configure::read('Security.salt') . '01234';

        foreach (['first@email.com', 'second@provider.com', 'example@myexample.com'] as $mail) {
            $encrypted = Security::encryptMail($mail);
            $this->assertEquals(Security::decryptMail($encrypted), $mail);

            $encrypted = Security::encryptMail($mail, $key);
            $this->assertEquals(Security::decryptMail($encrypted, $key), $mail);

            $encrypted = Security::encryptMail($mail, $key, $hmacSalt);
            $this->assertEquals(Security::decryptMail($encrypted, $key, $hmacSalt), $mail);
        }
    }

    /**
     * Test for `encryptMail()` method
     * @test
     */
    public function testEncryptMail()
    {
        $key = Configure::read('RecaptchaMailhide.encryptKey') . '01234';
        $hmacSalt = Security::getSalt() . '01234';

        foreach (['first@email.com', 'second@provider.com', 'example@myexample.com'] as $mail) {
            $this->assertNotEmpty(Security::encryptMail($mail));
            $this->assertNotEmpty(Security::encryptMail($mail, $key));
            $this->assertNotEmpty(Security::encryptMail($mail, $hmacSalt));
        }
    }
}
