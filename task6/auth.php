<?php
// auth.php
session_start();
include('../db.php');
$db = new PDO('mysql:host=localhost;dbname=' . $db_name, $db_login, $db_pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

function checkAuth() {
    global $db;
    try {
        $result = $db->query("SELECT login, password FROM admin");
        $row = $result->fetch(PDO::FETCH_ASSOC);
        if (empty($_SERVER['PHP_AUTH_USER']) ||
            empty($_SERVER['PHP_AUTH_PW']) ||
            $_SERVER['PHP_AUTH_USER'] != $row["login"] ||
            !password_verify($_SERVER['PHP_AUTH_PW'], $row["password"])) {
            header('HTTP/1.1 401 Unauthorized');
            header('WWW-Authenticate: Basic realm="My site"');
            exit('<h1>401 Требуется авторизация</h1>');
        }
    } catch(PDOException $e) {
        exit('Error : ' . $e->getMessage());
    }
}

checkAuth();
?>
