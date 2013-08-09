<?php
session_start();
require 'config.php';

$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWD);
$q = "SELECT * FROM user";
echo '<pre>';
foreach ($db->query($q) as $row) {
  print_r ($row);
}