<?php

require_once(dirname(__FILE__).'/../bootstrap/functional.php');

$browser = new sfTestFunctional(new sfBrowser());

test_form('/auto', $browser);
test_form('/manual', $browser);

function test_form($url, sfTestFunctional $browser)
{
  $browser
    ->get($url)
    
    ->info('  a) Check that the recaptcha form fields are present')
    ->with('response')->begin()
      ->isStatusCode(200)
      ->checkElement('textarea[name=recaptcha_challenge_field]', true)
      ->checkElement('input[name=recaptcha_response_field]', true)
    ->end()
    
    ->info('  b) submit the form with some fake values')
    ->click('submit', array(
      'recaptcha_challenge_field' => 'test_challenge',
      'recaptcha_response_field'  => 'i win!',
    ))

    ->info('  c) test the response, which outputs the recaptcha values submitted to the form')
    ->with('response')->begin()
      ->isStatusCode(200)
      ->checkElement('h1.captcha_challenge', '/test_challenge/')
      ->checkElement('h1.captcha_response', '/i win!/')
    ->end()
  ;
}