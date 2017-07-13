# reCAPTCHA Mailhide

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.txt)
[![Build Status](https://api.travis-ci.org/mirko-pagliai/cakephp-recaptcha-mailhide.svg?branch=master)](https://travis-ci.org/mirko-pagliai/cakephp-recaptcha-mailhide)
[![Coverage Status](https://img.shields.io/codecov/c/github/mirko-pagliai/cakephp-recaptcha-mailhide.svg?style=flat-square)](https://codecov.io/github/mirko-pagliai/cakephp-recaptcha-mailhide)

*reCAPTCHA Mailhide* is a CakePHP plugin that allows you to hide email addresses
using reCAPTCHA.  
It works by using the [crabstudio/Recaptcha](https://github.com/crabstudio/Recaptcha)
plugin, which must first be loaded and configured correctly.

## Installation
You can install the plugin via composer:

    $ composer require --prefer-dist mirko-pagliai/cakephp-recaptcha-mailhide
    
Then you have to edit `APP/config/bootstrap.php` to load the plugin:

    Plugin::load('RecaptchaMailhide', ['bootstrap' => true, 'routes' => true]);

Remember, **you must first load** the *crabstudio/Recaptcha* plugin. For example:

    Plugin::load('Recaptcha');
    Plugin::load('RecaptchaMailhide', ['bootstrap' => true, 'routes' => true]);

For more information on how to load the plugin, please refer to the 
[Cookbook](http://book.cakephp.org/3.0/en/plugins.html#loading-a-plugin).

Then you also need to set up a key to encrypt/decrypt email addresses:

    Configure::write('RecaptchaMailhide.encryptKey', 'thisIsAKeyForEncrypt12345678901234567890');

## Configuration
First, you have to load the `Recaptcha` component provided by the
*crabstudio/Recaptcha* plugin, as described [here](https://github.com/crabstudio/Recaptcha#load-component-and-configure).  
The component **must be loaded** inside the `initialize()` method of your
`AppController` class.

For more information on how to load the component, please refer to the 
[Cookbook](https://book.cakephp.org/3.0/en/controllers/components.html#configuring-components).

Then, you have to load the `Mailhide` helper:

    $this->loadHelper('RecaptchaMailhide.Mailhide');

For more information on how to load the helper, please refer to the 
[Cookbook](https://book.cakephp.org/3.0/en/views/helpers.html#configuring-helpers).

## Usage
You can now use the `link()` method provided by the `Mailhide` helper in your
template files. Example:

    echo $this->Mailhide->link('My mail', 'myname@mymail.com');

This will create a link. By clicking on the link, a popup will open and it will
contain the reCAPTCHA control. If the check was filled in correctly, the clear
email will be shown.

You can also use the email address as the title of the link. Example:

    echo $this->Mailhide->link('myname@mymail.com', 'myname@mymail.com');

In this case, the email will be obfuscated (*myn\*\*\*@mymail.com*) to be shown
as the title of the link.

The third parameter of the method can be used for link options. Example:

    echo $this->Mailhide->link('My mail', 'myname@mymail.com', ['class' => 'my-custom-class']);

## Versioning
For transparency and insight into our release cycle and to maintain backward 
compatibility, *reCAPTCHA Mailhide* will be maintained under the 
[Semantic Versioning guidelines](http://semver.org).
