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
use Cake\Http\Client\Adapter\Stream;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use RecaptchaMailhide\Utility\Security;

/**
 * MailhideControllerTest class
 */
class MailhideControllerTest extends TestCase
{
    use IntegrationTestTrait {
        IntegrationTestTrait::setUp as cakeSetUp;
        IntegrationTestTrait::controllerSpy as cakeControllerSpy;
    }

    /**
     * Called before every test method
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->cakeSetUp();

        $this->loadPlugins(['RecaptchaMailhide']);
    }

    /**
     * Adds additional event spies to the controller/view event manager
     * @param \Cake\Event\Event $event A dispatcher event
     * @param \Cake\Controller\Controller|null $controller Controller instance
     * @return void
     */
    public function controllerSpy($event, $controller = null)
    {
        $this->cakeControllerSpy($event, $controller);

        //See https://github.com/travis-ci/travis-ci/issues/6339
        $this->_controller->Recaptcha->setConfig('httpClientOptions', ['adapter' => Stream::class]);

        //Only for some test, it mocks the `Recaptcha` component, so the
        //  reCAPTCHA control returns a success
        if (in_array($this->getName(), ['testDisplayVerifyTrue', 'testDisplayInvalidMailValueOnQuery'])) {
            $this->_controller->Recaptcha = $this->getMockBuilder(get_class($this->_controller->Recaptcha))
                ->setConstructorArgs([new ComponentRegistry($this->_controller), $this->_controller->Recaptcha->getConfig()])
                ->setMethods(['verify'])
                ->getMock();

            $this->_controller->Recaptcha->method('verify')->will($this->returnValue(true));
        }

        //Only for the `testDisplayMissingRecaptchaComponent` test, unloads the
        //  `Recaptcha` component
        if ($this->getName() === 'testDisplayMissingRecaptchaComponent') {
            $this->_controller->components()->unload('Recaptcha');
            unset($this->_controller->Recaptcha);
        }
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

        foreach (['get', 'post'] as $method) {
            $this->{$method}($url);
            $this->assertResponseOk();
            $this->assertResponseNotContains($mail);

            $this->{$method}($url, ['g-recaptcha-response' => 'foo']);
            $this->assertResponseOk();
            $this->assertResponseNotContains($mail);
        }
    }

    /**
     * Test for `display()` method, with the reCAPTCHA control that returns a
     *  success
     * @test
     */
    public function testDisplayVerifyTrue()
    {
        $mail = 'test@example.com';

        $this->post($this->getDisplayActionUrl($mail), ['g-recaptcha-response' => 'foo']);
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
        $url = $this->getDisplayActionUrl('test@example.com');
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
