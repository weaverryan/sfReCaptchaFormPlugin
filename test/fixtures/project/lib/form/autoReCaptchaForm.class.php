<?php

// form that will have ReCaptcha enabled by default via app.yml
class autoReCaptchaForm extends sfFormSymfony
{
  public function configure()
  {
    $this->validatorSchema = new sfReCaptchaTestValidatorSchema();
    $this->widgetSchema->setNameFormat('testing[%s]');
  }
}