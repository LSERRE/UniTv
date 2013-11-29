<?php

 	require '../facebookSdk/src/facebook.php';

    $facebook = new Facebook(array(
        'appId'  => '213048858862502',
        'secret' => 'c8a611b2ce186c6c582c347ddcb22aa4'
    ));
        
  	$logoutUrl = $facebook->getLogoutUrl(array(
           /*'scope'   => 'read_stream, user_birthday, user_location, user_work_history, user_hometown, user_photos',*/
           'next' => 'http://unitv.fr',
  	));
		session_destroy();
	  header('Location: '.$logoutUrl);

?>