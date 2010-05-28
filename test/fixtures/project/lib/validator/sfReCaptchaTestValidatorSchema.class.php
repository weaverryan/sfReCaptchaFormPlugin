<?php
// used in the test forms, allows us to see exactly what form values were validated
class sfReCaptchaTestValidatorSchema extends sfValidatorSchema
{
  protected
    $_rawValues;
  
  public function clean($values)
  {
    $this->_rawValues = $values;
    
    parent::clean($values);
  }

  public function getRawValues()
  {
    return $this->_rawValues;
  }
}