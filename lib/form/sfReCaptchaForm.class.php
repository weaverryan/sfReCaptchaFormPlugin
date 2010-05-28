<?php

/**
 * Acts as a "form" class for this plugin. This listenes to several events:
 *   * form.filter_values
 *   * form.post_configure
 *   * form.method_not_found
 * 
 * The last allows any public method in this class to be called from within
 * any form class (e.g. $this->embedRecaptcha())
 * 
 * @package     sfReCaptchaFormPlugin
 * @subpackage  form
 * @author      Jonathan H. Wage <jonwage@gmail.com>
 * @author      Ryan Weaver <ryan@thatsquality.com>
 */

class sfReCaptchaForm
{
  // used internally for testing this plugin
  public static $testingMode = false;

  /*
   * The subject of the extend (method_not_found) event
   */
  protected $_subject;

  protected
    $_context,
    $_forms,
    $_publicKey,
    $_privateKey;

  /**
   * Class constructor
   * 
   * @param sfContext $context
   * @param array $forms An array of form classes to embed ReCaptcha into
   * @param string $publicKey The public recaptcha key
   * @param string $privateKey The private recaptcha key
   */
  public function __construct(sfContext $context, $forms, $publicKey, $privateKey)
  {
    $this->_context = $context;
    $this->_forms = $forms;
    $this->_publicKey = $publicKey;
    $this->_privateKey = $privateKey;
  }

  /**
   * Call this from configure in your form to forcefully embed ReCaptcha
   */
  public function embedRecaptcha()
  {
    $this->_forms[] = get_class($this->_subject);
    $this->_embedRecaptcha($this->_subject);
  }

  /**
   * Implements form.method_not_found
   * 
   * @return boolean
   */
  public function hasRecaptcha()
  {
    return $this->_hasRecaptcha($this->_subject);
  }

  /**
   * Whether or not this form should have a recaptcha field added to it
   * 
   * This is called by the following events so that the addition and processing
   * of this field occurs automatically:
   *   - form.post_configure Adds the recaptcha field
   *   - form.filter_values: Processes the submitted value
   * 
   * @return boolean
   */
  protected function _hasRecaptcha(sfForm $form)
  {
    // No recaptcha in test environment
    if (sfConfig::get('sf_environment') === 'test' && !self::$testingMode)
    {
      return false;
    }
    $class = get_class($form);

    return in_array($class, $this->_forms);
  }

  /**
   * Listens to the form.filter_values event
   */
  public function listenToFormFilterValues(sfEvent $event, $values)
  {
    $form = $event->getSubject();
    if ($this->_hasRecaptcha($form))
    {
      $request = $this->_context->getRequest();
      $captcha = array(
        'recaptcha_challenge_field' => $request->getParameter('recaptcha_challenge_field'),
        'recaptcha_response_field'  => $request->getParameter('recaptcha_response_field'),
      );
      $values = array_merge($values, array('captcha' => $captcha));
    }

    return $values;
  }

  /**
   * Listens to the form.post_configure event
   */
  public function listenToFormPostConfigure(sfEvent $event)
  {
    if ($this->_hasRecaptcha($event->getSubject()))
    {
      $this->_embedRecaptcha($event->getSubject());
    }
  }

  /**
   * Embeds recaptcha into the given form
   */
  protected function _embedRecaptcha(sfForm $form)
  {
    if (!$this->_publicKey || !$this->_privateKey) {
      throw new sfException('You must specify the recaptcha public and private key in your sympal configuration');
    }

    $widgetSchema = $form->getWidgetSchema();
    $validatorSchema = $form->getValidatorSchema();

    $widgetSchema['captcha'] = new sfWidgetFormReCaptcha(array(
      'public_key' => $this->_publicKey
    ));

    $validatorSchema['captcha'] = new sfValidatorReCaptcha(array(
      'private_key' => $this->_privateKey
    ));
  }

  /**
   * Listener method for method_not_found events
   * 
   * @example
   * $extendedUser = new myExtendedUser(); // extends sfExtendClass
   * $dispatcher->connect('user.method_not_found', array($extendedUser, 'extend'));
   */
  public function extend(sfEvent $event)
  {
    $this->_subject = $event->getSubject();
    $method = $event['method'];
    $arguments = $event['arguments'];

    if (method_exists($this, $method))
    {
      $result = call_user_func_array(array($this, $method), $arguments);

      $event->setReturnValue($result);

      return true;
    }
    else
    {
      return false;
    }
  }

  /**
   * @return array
   */
  public function getForms()
  {
    return $this->_forms;
  }

  /**
   * @return string
   */
  public function getPublicKey()
  {
    return $this->_publicKey;
  }

  /**
   * @return string
   */
  public function getPrivateKey()
  {
    return $this->_privateKey;
  }
}




