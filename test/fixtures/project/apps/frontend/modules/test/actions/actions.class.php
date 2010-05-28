<?php

class testActions extends sfActions
{
  public function executeAuto(sfWebRequest $request)
  {
    $this->form = new autoReCaptchaForm();
    $this->setTemplate('form');
    
    $widgetSchema = $this->form->getWidgetSchema();
  }

  public function executeManual(sfWebRequest $request)
  {
    $this->form = new manualReCaptchaForm();
    $this->setTemplate('form');
  }

  public function executeSubmit(sfWebRequest $request)
  {
    $this->form = new autoReCaptchaForm();
    $this->form->bind($request->getParameter($this->form->getName()));
    
    $this->setTemplate('submit');
  }
}