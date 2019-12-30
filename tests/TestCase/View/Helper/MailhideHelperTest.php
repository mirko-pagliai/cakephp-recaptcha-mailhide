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
namespace RecaptchaMailhide\Test\TestCase\View\Helper;

use Cake\View\View;
use MeTools\TestSuite\TestCase;
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
     * Called before every test method
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->Mailhide = new MailhideHelper(new View());
    }

    /**
     * Internal method to get the "onClick" regex
     * @param string $title Link title
     * @return string
     */
    protected function getOnClickRegex(string $title): string
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
        $result = $this->Mailhide->link('My address', 'test@example.com', ['class' => 'custom-class', 'title' => 'custom title']);
        $this->assertHtml($expected, $result);
    }
}
