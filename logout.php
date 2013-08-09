<?php

require '_includes.php';

$facebook = new Facebook(array(
  'appId'  => '339313339480239',
  'secret' => '826a85242cc429e80eb921ed5dffe56b',
));

//$logout = $facebook->getLogoutUrl(array('next' => "http://{$_SERVER['SERVER_NAME']}/"));
session_destroy();
header("location: /index.php");
