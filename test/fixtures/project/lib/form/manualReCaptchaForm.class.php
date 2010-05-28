<?php

// form that will manually embed ReCaptcha
class manualReCaptchaForm extends sfFormSymfony
{
  public function configure()
  {
    $this->validatorSchema = new sfReCaptchaTestValidatorSchema();
    $this->embedRecaptcha();
    
    $this->widgetSchema->setNameFormat('testing[%s]');
  }
}