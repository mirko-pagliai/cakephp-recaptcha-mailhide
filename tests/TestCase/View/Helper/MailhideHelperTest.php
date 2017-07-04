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
namespace RecaptchaMailhide\Test\TestCase\View\Helper;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use RecaptchaMailhide\View\Helper\MailhideHelper;

/**
 * MailhideHelperTest class
 */
class MailhideHelperTest extends TestCase
{
    /**
     * @var \RecaptchaMailhide\View\Helper\MailhideHelper
     */
    protected $Mailhide;

    /**
     * Setup the test case, backup the static object values so they can be
     * restored. Specifically backs up the contents of Configure and paths in
     *  App if they have not already been backed up
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->Mailhide = new MailhideHelper(new View);
    }

    /**
     * Teardown any static object changes and restore them
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();

        unset($this->Mailhide);
    }

    /**
     * Test for `obfuscate()` method
     * @return void
     * @test
     */
    public function testObfuscate()
    {
        $result = $this->Mailhide->obfuscate('myname@mymail.com');
        $expected = 'myn***@mymail.com';
        $this->assertEquals($expected, $result);

        $result = $this->Mailhide->obfuscate('firstnameandlastname@example.it');
        $expected = 'firstnamea**********@example.it';
        $this->assertEquals($expected, $result);

        $result = $this->Mailhide->obfuscate('invalidmail');
        $expected = 'inval*****';
        $this->assertEquals($expected, $result);

        $result = $this->Mailhide->obfuscate('@invalidmail');
        $expected = '@invalidmail';
        $this->assertEquals($expected, $result);
    }
}
