<?php
	
require './facebookSdk/src/facebook.php';
 
$facebook = new Facebook(array(
  'appId'  => '213048858862502',
  'secret' => '//unitv.fr/index.php',
));

// Obtenir le User ID
$user = $facebook->getUser();

if ($user) {
  try {
    $user_profile = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}

print_r($user);

// URL de Login ou de logout
if ($user) {
  $logoutUrl = $facebook->getLogoutUrl();
} else {
  $loginUrl = $facebook->getLoginUrl();
}

?>