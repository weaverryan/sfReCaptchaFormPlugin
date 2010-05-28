sfReCaptchaFormPlugin
=====================

Very simple, test, plugin to add reCAPTCHA to a symfony form.

Usage
-----

To add recaptcha to your form, simply call `embedRecaptcha()` from inside
the `configure()` method of your form:

    class myForm extends BaseForm
    {
      public function configure()
      {
        $this->embedRecaptch();
      }
    }

In the view, output a field called `captcha` as you normally would:

    <?php echo $form['captcha']->render() ?>

The validation of the captcha will take place automatically.

Installation
------------

To install this plugin from git:

    git submodule add git://github.com/weaverryan/sfReCaptchaFormPlugin.git plugins/sfReCaptchaFormPlugin
    git submodule init

Configuration
-------------

The only configuration needed are the reCAPTCHA public and private keys.
First, obtain a public and private key via
[https://www.google.com/recaptcha/admin/create](https://www.google.com/recaptcha/admin/create).

Next, in your application's `app.yml` file, add the following:

    all:
      recaptcha:
        recaptcha_public_key:   XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
        recaptcha_private_key:  XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

Advanced Configuration
----------------------

The `app.yml` file that comes packaged with the plugin shows all of the
configuration options:

Most notably, you can automatically enable ReCaptcha on any form by
specifying the form class in `app.yml`. If you do this, you won't need
to call `embedRecaptcha()` from within your form:

    all:
      recaptcha:
        forms:
          myProductForm:    true

Common Problems
---------------

If you receive the error "Call to undefined method testForm::embedRecaptcha.",
then most likely:

 * The plugin is not installed or enabled

 * Your form extends only `sfForm`. It should extends `sfFormSymfony`. If
   your form is a Doctrine or Propel form, or your form extends `BaseForm`,
   then this is not the problem.

The Fine Details
----------------

This plugin was taken from [sympal CMF](http://www.sympalphp.org) and was
developed by both Jon Wage and Ryan Weaver.

A bug tracker is available for this plugin at
[http://redmine.sympalphp.org/projects/recaptchaplugin](http://redmine.sympalphp.org/projects/recaptchaplugin).

If you have questions, comments or anything else, email me at ryan [at] thatsquality.com