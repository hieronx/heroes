<?php
require_once '_includes.php';

$facebook = new Facebook(array(
  'appId'  => FACEBOOK_ID,
  'secret' => FACEBOOK_SECRET,
  'code' => $_GET['code'],
));

try {

	// Als het goed is hebben we hier een user...
	$user = $facebook->getUser();

	// En een access token...
	$access_token = $facebook->getAccessToken();

	if (!$user) {
	 exit('Niet ingelogd.');
	}

	$user_profile = $facebook->api('/me');

	$q = $db->prepare("INSERT INTO user 
	                  (facebook_uid, firstname, lastname, facebook_token, facebook_info, creation_date) 
	                  VALUES (
	                    '" . $user_profile ['id'] . "',
	                    '" . $user_profile ['first_name'] . "',
	                    '" . $user_profile ['last_name'] . "',
	                    '" . $access_token . "',
	                    '" . json_encode($user_profile) . "',
	                    NOW()
	                  )");
	$q->execute();
	
	header('location: challenges.php');

} catch (FacebookApiException $e) {
	echo '<pre>'.htmlspecialchars(print_r($e, true)).'</pre>';
	$user = null;
}
