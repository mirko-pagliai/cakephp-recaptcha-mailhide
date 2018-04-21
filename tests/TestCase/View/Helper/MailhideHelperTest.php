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
namespace RecaptchaMailhide\Test\TestCase\View\Helper;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use RecaptchaMailhide\View\Helper\MailhideHelper;
use Tools\ReflectionTrait;

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
     * Test for `obfuscate()` method
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
            $this->assertEquals($expected, $this->invokeMethod($this->Mailhide, 'obfuscate', [$mail]));
        }
    }

    /**
     * Test for `link()` method
     * @test
     */
    public function testLink()
    {
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
        $this->assertHtml($expected, $this->Mailhide->link('My address', 'test@example.com'));

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
        $this->assertHtml($expected, $this->Mailhide->link('test@example.com', 'test@example.com'));

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
