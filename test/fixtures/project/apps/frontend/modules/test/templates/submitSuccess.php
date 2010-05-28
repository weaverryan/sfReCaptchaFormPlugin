<?php 
  $values = $form->getValidatorSchema()->getRawValues();
?>

<h1 class="captcha_challenge"><?php echo $values['captcha']['recaptcha_challenge_field'] ?></h1>
<h1 class="captcha_response"><?php echo $values['captcha']['recaptcha_response_field'] ?></h1>