<?php

error_reporting(E_NONE);
ini_set("display_errors", 0);

@session_start();

require dirname(__FILE__).'/app/config.php';
require dirname(__FILE__).'/lib/facebook/facebook.php';
require dirname(__FILE__).'/lib/class.embedcodes.php';

try {
  $db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWD);
} catch (Exception $e) {
  exit('Cannot connect to database.');
}

$facebook = new Facebook(array(
  'appId'  => 'xxxxxxx',
  'secret' => 'xxxxxxx',
));

function findUser($facebook_id) {
	global $db;
	$user = $db->query("SELECT * FROM user WHERE facebook_uid = '{$facebook_id}'")->fetch();
	return $user;
}

if (!isset($_GET['code'])) {	
	// See if there is a user from a cookie...
	$CURRENT_USER = $facebook->getUser() ? findUser($facebook->getUser()) : false;
}
