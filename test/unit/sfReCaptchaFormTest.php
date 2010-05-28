<?php

require_once(dirname(__FILE__).'/../bootstrap/functional.php');
require_once $configuration->getSymfonyLibDir().'/vendor/lime/lime.php';

$t = new lime_test(9);

$t->info('1 - Test that the configuration was set correctly on the form class');
  $formListeners = $configuration->getEventDispatcher()->getListeners('form.method_not_found');
  $formObj = $formListeners[0][0];

  $t->is(get_class($formObj), 'sfTestReCaptchaForm', 'The form class is properly used from app.yml');
  $t->is($formObj->getForms(), array('autoReCaptchaForm'), '->getForms() returns the forms specified in app.yml');
  $t->is($formObj->getPublicKey(), 'apple', '->getPublicKey() returns the public key from app.yml');
  $t->is($formObj->getPrivateKey(), 'banana', '->getPrivateKey() returns the private key from app.yml');

$t->info('2 - Test the form.method_not_found implementation on the form object');
  $form = new sfFormSymfony(); // an ordinary "symfony" form object
  $t->is($form->testMethodNotFound(), true, 'Calling a method defined in sfTestReCaptchaForm is done successfully');
  
  $t->info('  2.1 - Embed the recaptcha and check');
  $form->embedRecaptcha();
  test_for_recaptcha($t, $form);

  $t->info('  2.2 - Test that the autoReCaptchaForm form automatically has recaptcha');
  $form = new autoReCaptchaForm();
  test_for_recaptcha($t, $form);


// helper to test if a form has recaptcha embedded
function test_for_recaptcha(lime_test $t, sfFormSymfony $form)
{
  $widgetSchema = $form->getWidgetSchema();
  $validatorSchema = $form->getValidatorSchema();
  
  $t->is($widgetSchema['captcha'] && get_class($widgetSchema['captcha']), 'sfWidgetFormReCaptcha', '->embedRecaptcha() sets the recaptcha widget correctly.');
  $t->is($validatorSchema['captcha'] && get_class($validatorSchema['captcha']), 'sfValidatorReCaptcha', '->embedRecaptcha() sets the recaptcha validator correctly.');
}