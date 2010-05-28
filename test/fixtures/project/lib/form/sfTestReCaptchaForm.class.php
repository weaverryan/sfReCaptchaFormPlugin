<?php
// used as the "extended" form for testing
class sfTestReCaptchaForm extends sfReCaptchaForm
{
  // used in the tests to test the form.method_not_found implementation
  public function testMethodNotFound()
  {
    return true;
  }
}