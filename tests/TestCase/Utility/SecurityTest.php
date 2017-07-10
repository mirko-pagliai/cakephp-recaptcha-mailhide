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
