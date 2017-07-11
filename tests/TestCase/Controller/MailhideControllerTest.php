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
        //Only for the `testDisplayVerifyTrue()` test, it mocks the `Recaptcha`
        //  component, so the reCAPTCHA control returns a success
        if ($this->getName() === 'testDisplayVerifyTrue') {
            $controller->Recaptcha = $this->getMockBuilder(get_class($controller->Recaptcha))
                ->setConstructorArgs([new ComponentRegistry($controller), $controller->Recaptcha->getConfig()])
                ->setMethods(['verify'])
                ->getMock();

            $controller->Recaptcha->method('verify')
                ->will($this->returnValue(true));
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
}
