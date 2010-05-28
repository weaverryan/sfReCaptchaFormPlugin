<?php

/**
 * Configuration class for sfReCaptchaPlugin
 * 
 * @package     sfReCaptchaPlugin
 * @subpackage  config
 * @author      Ryan Weaver <ryan@thatsquality.com>
 */
class sfReCaptchaPluginConfiguration extends sfPluginConfiguration
{
  public static $dependencies = array(
    'sfFormExtraPlugin',
  );

  /**
   * Initializes the plugin
   */
  public function initialize()
  {
    $this->dispatcher->connect('context.load_factories', array($this, 'bootstrap'));
  }

  /**
   * Boostraps the plugin
   * 
   * Listens to the context.load_factories
   */
  public function bootstrap(sfEvent $event)
  {
    $this->_checkDependencies();
    
    $form = $this->_createFormObject($event->getSubject());
    
    // extend the form class
    $this->dispatcher->connect('form.method_not_found', array($form, 'extend'));

    // Register a listener on the form.filter_values event
    $this->dispatcher->connect('form.filter_values', array($form, 'listenToFormFilterValues'));

    // Register a listener on the form.post_configure event
    $this->dispatcher->connect('form.post_configure', array($form, 'listenToFormPostConfigure'));
  }

  /**
   * Creates an instance of the sfReCaptchaForm from the configuratiin
   * 
   * return sfReCaptchaForm
   */
  protected function _createFormObject(sfContext $context)
  {
    $class = sfConfig::get('app_recaptcha_extended_form_class', 'sfReCaptchaForm');

    // find the recaptcha forms
    $forms = sfConfig::get('app_recaptcha_forms');
    foreach ($forms as $form => $bool)
    {
      if (!$bool)
      {
        unset($forms[$form]);
      }
    }
    $forms = array_keys($forms);
    
    $publicKey = sfConfig::get('app_recaptcha_public_key');
    $privateKey = sfConfig::get('app_recaptcha_private_key');

    return new $class($context, $forms, $publicKey, $privateKey);
  }

  /**
   * Checks to make sure this plugins has the necessary depedencies fulfilled
   * 
   * @throws sfException
   */
  protected function _checkDependencies()
  {
    foreach (self::$dependencies as $dependency)
    {
      try
      {
        $this->configuration->getPluginConfiguration($dependency);
      }
      catch (InvalidArgumentException $e)
      {
        throw new sfException(sprintf(
          'sfReCaptchaPlugin requires the %s plugin, which is either not installed or not enabled.',
          $dependency
        ));
      }
    }
  }
}