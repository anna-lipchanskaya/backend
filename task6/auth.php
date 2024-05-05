<?php
session_start(); // Начало сессии
require_once('db.php');   
function checkAuth() {
        $query = "SELECT login, password FROM admin";
        $row = db_row(Query($query));
    
    if (empty($_SERVER['PHP_AUTH_USER']) ||
        empty($_SERVER['PHP_AUTH_PW']) ||
        $_SERVER['PHP_AUTH_USER'] != $row["login"] ||
        !password_verify($_SERVER['PHP_AUTH_PW'], $row["password"])) {
      header('HTTP/1.1 401 Unauthorized');
      header('WWW-Authenticate: Basic realm="My site"');
      exit('<h1>401 Требуется авторизация</h1>');
    }
}
checkAuth();
?>
