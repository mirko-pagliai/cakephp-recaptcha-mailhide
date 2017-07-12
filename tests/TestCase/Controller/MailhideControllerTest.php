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
namespace RecaptchaMailhide\Test\TestCase\Controller;

use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\IntegrationTestCase;
use RecaptchaMailhide\Utility\Security;

/**
 * MailhideControllerTest class
 */
class MailhideControllerTest extends IntegrationTestCase
{
    /**
     * Adds additional event spies to the controller/view event manager
     * @param \Cake\Event\Event $event A dispatcher event
     * @param \Cake\Controller\Controller|null $controller Controller instance
     * @return void
     */
    public function controllerSpy($event, $controller = null)
    {
        //Only for some test, it mocks the `Recaptcha` component, so the
        //  reCAPTCHA control returns a success
        if (in_array($this->getName(), ['testDisplayVerifyTrue', 'testDisplayInvalidMailValueOnQuery'])) {
            $controller->Recaptcha = $this->getMockBuilder(get_class($controller->Recaptcha))
                ->setConstructorArgs([new ComponentRegistry($controller), $controller->Recaptcha->getConfig()])
                ->setMethods(['verify'])
                ->getMock();

            $controller->Recaptcha->method('verify')
                ->will($this->returnValue(true));
        }

        //Only for the `testDisplayMissingRecaptchaComponent` test, unloads the
        //  `Recaptcha` component
        if ($this->getName() === 'testDisplayMissingRecaptchaComponent') {
            $controller->components()->unload('Recaptcha');
            unset($controller->Recaptcha);
        }

        return parent::controllerSpy($event, $controller);
    }

    /**
     * Internal method to the url of the `display()` action
     * @param string $mail
     * @return array
     */
    protected function getDisplayActionUrl($mail)
    {
        return ['_name' => 'mailhide', '?' => ['mail' => Security::encryptMail($mail)]];
    }

    /**
     * Test for `display()` method
     * @test
     */
    public function testDisplay()
    {
        $mail = 'test@example.com';
        $url = $this->getDisplayActionUrl($mail);

        $this->get($url);
        $this->assertResponseOk();
        $this->assertResponseNotContains($mail);

        $this->post($url);
        $this->assertResponseOk();
        $this->assertResponseNotContains($mail);

        $this->post($url, ['g-recaptcha-response' => 'foo']);
        $this->assertResponseOk();
        $this->assertResponseNotContains($mail);
    }

    /**
     * Test for `display()` method, with the reCAPTCHA control that returns a
     *  success
     * @test
     */
    public function testDisplayVerifyTrue()
    {
        $mail = 'test@example.com';
        $url = $this->getDisplayActionUrl($mail);

        $this->post($url, ['g-recaptcha-response' => 'foo']);
        $this->assertResponseOk();
        $this->assertResponseContains($mail);
    }

    /**
     * Test for `display()` method, missing mail on query
     * @test
     */
    public function testDisplayMissingMailOnQuery()
    {
        $this->get(['_name' => 'mailhide']);
        $this->assertResponseError();
        $this->assertResponseContains('Missing mail value');
    }

    /**
     * Test for `display()` method, with an invalid mail value on query
     * @test
     */
    public function testDisplayInvalidMailValueOnQuery()
    {
        $mail = 'test@example.com';
        $url = $this->getDisplayActionUrl($mail);
        $url['?']['mail'] .= 'foo';

        $this->post($url, ['g-recaptcha-response' => 'foo']);
        $this->assertResponseError();
        $this->assertResponseContains('Invalid mail value');
    }

    /**
     * Test for `display()` method, missing `Recaptcha` component
     * @test
     */
    public function testDisplayMissingRecaptchaComponent()
    {
        $this->get($this->getDisplayActionUrl('test@example.com'));
        $this->assertResponseFailure();
        $this->assertResponseContains('Missing Recaptcha component');
    }
}
