<?php

global $db;
$db = new PDO('mysql:host=' . conf('db_host') . ';dbname=' . conf('db_name'), conf('db_user'), conf('db_psw'),
  array(PDO::MYSQL_ATTR_FOUND_ROWS => true, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

function db_row($stmt) {
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

function db_error() {
  global $db;
  return $db->errorInfo();
}
?>
