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
use Reflection\ReflectionTrait;

/**
 * MailhideHelperTest class
 */
class MailhideHelperTest extends TestCase
{
    use ReflectionTrait;

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
     * Internal method to get the "onClick" regex
     * @param string $title Link title
     * @return string
     */
    protected function getOnClickRegex($title)
    {
        return sprintf(
            preg_quote('window.open(\'%s\',\'' . $title . '\',\'resizable,height=547,width=334\'); return false;', '/'),
            '[^\']+',
            '\d+',
            '\d+'
        );
    }

    /**
     * Test for `_obfuscate()` method
     * @test
     */
    public function testObfuscate()
    {
        foreach ([
            'myname@mymail.com' => 'myn***@mymail.com',
            'firstandlastname@example.it' => 'firstand********@example.it',
            'invalidmail' => 'inval*****',
            '@invalidmail' => '@invalidmail',
        ] as $mail => $expected) {
            $result = $this->invokeMethod($this->Mailhide, '_obfuscate', [$mail]);
            $this->assertEquals($expected, $result);
        }
    }

    /**
     * Test for `link()` method
     * @test
     */
    public function testLink()
    {
        $result = $this->Mailhide->link('My address', 'test@example.com');
        $expected = [
            'a' => [
                'href',
                'onClick' => 'preg:/' . $this->getOnClickRegex('My address') . '/',
                'class' => 'recaptcha-mailhide',
                'title' => 'My address',
            ],
            'My address',
            '/a',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Mailhide->link('test@example.com', 'test@example.com');
        $expected = [
            'a' => [
                'href',
                'onClick' => 'preg:/' . $this->getOnClickRegex('te**@example.com') . '/',
                'class' => 'recaptcha-mailhide',
                'title' => 'te**@example.com',
            ],
            'te**@example.com',
            '/a',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Mailhide->link('My address', 'test@example.com', ['class' => 'custom-class', 'title' => 'custom title']);
        $expected = [
            'a' => [
                'href',
                'onClick' => 'preg:/' . $this->getOnClickRegex('My address') . '/',
                'class' => 'custom-class',
                'title' => 'custom title',
            ],
            'My address',
            '/a',
        ];
        $this->assertHtml($expected, $result);
    }
}
