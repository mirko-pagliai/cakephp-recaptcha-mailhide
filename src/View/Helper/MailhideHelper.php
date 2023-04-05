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
namespace RecaptchaMailhide\View\Helper;

use Cake\Routing\Router;
use Cake\View\Helper;
use RecaptchaMailhide\Utility\Security;

/**
 * MailhideHelper
 * @property \Cake\View\Helper\HtmlHelper $Html
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
    protected function obfuscate(string $mail): string
    {
        return preg_replace_callback('/^([^@]+)(.*)$/', function ($matches): string {
            $length = (int)floor(strlen($matches[1]) / 2);
            $name = substr($matches[1], 0, $length) . str_repeat('*', $length);

            return $name . $matches[2];
        }, $mail) ?: '';
    }

    /**
     * Creates a link for the page where you enter the code and from which the clear email address will be displayed
     * @param string $title Link title. If it is the email address, it will be obfuscated
     * @param string $mail Email for which you want to create the link. It will not be shown clearly
     * @param array $options Array of options and HTML attributes
     * @return string An `<a />` element
     * @uses obfuscate()
     */
    public function link(string $title, string $mail, array $options = []): string
    {
        //Obfuscates the title, if the title is the email address
        $title = filter_var($title, FILTER_VALIDATE_EMAIL) ? $this->obfuscate($title) : $title;

        $mail = Security::encryptMail($mail);
        $url = Router::url(['_name' => 'mailhide', '?' => compact('mail')], true);

        $options['onClick'] = sprintf('window.open(\'%s\',\'%s\',\'resizable,height=547,width=334\'); return false;', $url, $title);
        $options += ['class' => 'recaptcha-mailhide', 'title' => $title];

        return $this->Html->link($title, $url, ['escape' => false] + $options);
    }
}
