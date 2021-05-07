# reCAPTCHA Mailhide

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.txt)
[![Build Status](https://api.travis-ci.org/mirko-pagliai/cakephp-recaptcha-mailhide.svg?branch=master)](https://travis-ci.org/mirko-pagliai/cakephp-recaptcha-mailhide)
[![codecov](https://codecov.io/gh/mirko-pagliai/cakephp-recaptcha-mailhide/branch/master/graph/badge.svg)](https://codecov.io/gh/mirko-pagliai/cakephp-recaptcha-mailhide)
[![Build status](https://ci.appveyor.com/api/projects/status/hal81mkbmwcmfbmi?svg=true)](https://ci.appveyor.com/project/mirko-pagliai/cakephp-recaptcha-mailhide)
[![CodeFactor](https://www.codefactor.io/repository/github/mirko-pagliai/cakephp-recaptcha-mailhide/badge)](https://www.codefactor.io/repository/github/mirko-pagliai/cakephp-recaptcha-mailhide)

*reCAPTCHA Mailhide* is a CakePHP plugin that allows you to hide email addresses
using reCAPTCHA.
It works by using the [crabstudio/Recaptcha](https://github.com/crabstudio/Recaptcha)
plugin, which must first be loaded and configured correctly.

Did you like this plugin? Its development requires a lot of time for me.
Please consider the possibility of making [a donation](//paypal.me/mirkopagliai):
even a coffee is enough! Thank you.

[![Make a donation](https://www.paypalobjects.com/webstatic/mktg/logo-center/logo_paypal_carte.jpg)](//paypal.me/mirkopagliai)

## Installation
You can install the plugin via composer:

```bash
$ composer require --prefer-dist mirko-pagliai/cakephp-recaptcha-mailhide
```

Then you have to load the plugin. For more information on how to load the plugin,
please refer to the [Cookbook](//book.cakephp.org/4.0/en/plugins.html#loading-a-plugin).

Simply, you can execute the shell command to enable the plugin:
```bash
bin/cake plugin load RecaptchaMailhide
```
This would update your application's bootstrap method.

Then you also need to set up a key to encrypt/decrypt email addresses:

```php
Configure::write('RecaptchaMailhide.encryptKey', 'thisIsAKeyForEncrypt12345678901234567890');
```

### Installation on older CakePHP and PHP versions
Recent packages and the master branch require at least CakePHP 4.0 and PHP 7.2.
Instead, the [cakephp3](//github.com/mirko-pagliai/cakephp-recaptcha-mailhide/tree/cakephp3) branch
requires at least PHP 5.6.

In this case, you can install the package as well:
```bash
$ composer require --prefer-dist mirko-pagliai/cakephp-recaptcha-mailhide:dev-cakephp3
```

Note that the `cakephp3` branch will no longer be updated as of May 7, 2021,
except for security patches, and it matches the
[1.4.7](//github.com/mirko-pagliai/cakephp-recaptcha-mailhide/releases/tag/1.4.7) version.

## Configuration
First, you have to load the `Recaptcha` component provided by the
*crabstudio/Recaptcha* plugin, as described [here](https://github.com/crabstudio/Recaptcha#load-component-and-configure).
The component **must be loaded** inside the `initialize()` method of your
`AppController` class.

For more information on how to load the component, please refer to the
[Cookbook](https://book.cakephp.org/4/en/controllers/components.html#configuring-components).

Then, you have to load the `Mailhide` helper:

```php
$this->loadHelper('RecaptchaMailhide.Mailhide');
```

For more information on how to load the helper, please refer to the
[Cookbook](https://book.cakephp.org/4/en/views/helpers.html#configuring-helpers).

## Usage
You can now use the `link()` method provided by the `Mailhide` helper in your
template files. Example:

```php
echo $this->Mailhide->link('My mail', 'myname@mymail.com');
```

This will create a link. By clicking on the link, a popup will open and it will
contain the reCAPTCHA control. If the check was filled in correctly, the clear
email will be shown.

You can also use the email address as the title of the link. Example:

```php
echo $this->Mailhide->link('myname@mymail.com', 'myname@mymail.com');
```

In this case, the email will be obfuscated (*myn\*\*\*@mymail.com*) to be shown
as the title of the link.

The third parameter of the method can be used for link options. Example:

```php
echo $this->Mailhide->link('My mail', 'myname@mymail.com', ['class' => 'my-custom-class']);
```

## Versioning
For transparency and insight into our release cycle and to maintain backward
compatibility, *reCAPTCHA Mailhide* will be maintained under the
[Semantic Versioning guidelines](http://semver.org).
