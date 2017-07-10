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
namespace RecaptchaMailhide\View\Helper;

use Cake\Routing\Router;
use Cake\View\Helper;
use RecaptchaMailhide\Utility\Security;

/**
 * MailhideHelper
 */
class MailhideHelper extends Helper
{
    /**
     * Helpers
     * @var array
     */
    public $helpers = ['Html'];

    /**
     * Internal method to obfuscate an email address
     * @param string $mail Mail address
     * @return string
     */
    protected function _obfuscate($mail)
    {
        return preg_replace_callback('/^([^@]+)(.*)$/', function ($matches) {
            $lenght = floor(strlen($matches[1]) / 2);

            $name = substr($matches[1], 0, $lenght) . str_repeat('*', $lenght);

            return $name . $matches[2];
        }, $mail);
    }

    /**
     * Creates a link for the page where you enter the code and from which the
     *  clear email address will be displayed
     * @param string $title Link title. If it is the email address, it will be
     *  obfuscated
     * @param string $mail Email for which you want to create the link. It
     *  will not be shown clearly
     * @param array $options Array of options and HTML attributes
     * @return string An `<a />` element
     * @uses \RecaptchaMailhide\Utility\Security::encryptMail()
     * @uses _obfuscate()
     */
    public function link($title, $mail, array $options = [])
    {
        //Obfuscates the title, if the title is the email address
        if (filter_var($title, FILTER_VALIDATE_EMAIL)) {
            $title = $this->_obfuscate($title);
        }

        $mail = Security::encryptMail($mail);
        $url = Router::url(['_name' => 'mailhide', '?' => compact('mail')], true);

        $options['escape'] = false;
        $options['onClick'] = 'window.open(\'' . $url . '\',\'' . $title . '\',\'resizable,height=547,width=334\'); return false;';

        $options += ['class' => 'recaptcha-mailhide', 'title' => $title];

        return $this->Html->link($title, $url, $options);
    }
}
