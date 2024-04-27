<?php

/**
 * Файл login.php для не авторизованного пользователя выводит форму логина.
 * При отправке формы проверяет логин/пароль и создает сессию,
 * записывает в нее логин и id пользователя.
 * После авторизации пользователь перенаправляется на главную страницу
 * для изменения ранее введенных данных.
 **/

// Отправляем браузеру правильную кодировку,
// файл login.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// В суперглобальном массиве $_SESSION хранятся переменные сессии.
// Будем сохранять туда логин после успешной авторизации.
$session_started = false;
if (session_start() && !empty($_COOKIE[session_name()])) {
    $session_started = true;
    if (!empty($_SESSION['login'])) {
        // Если есть логин в сессии, то пользователь уже авторизован.
        if (!empty($_COOKIE['logout'])) {
            setcookie('logout', '', 100000);
            // Выход пользователя из сессии
            session_destroy();
            exit();
        }

        // Делаем перенаправление на форму.
        header('Location: ./');
        exit();
    }
}

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
?>

<form action="" method="post">
  <input name="login" />
  <input name="password" />
  <input type="submit" value="Войти" />
</form>

<?php
}
// Иначе, если запрос был методом POST, т.е. нужно сделать авторизацию с записью логина в сессию.
else {
  // TODO: Проверть есть ли такой логин и пароль в базе данных.
  // Выдать сообщение об ошибках.

    $user = 'u67440'; // Заменить на ваш логин uXXXXX
    $pass = '7848123'; // Заменить на пароль
    $db = new PDO('mysql:host=localhost;dbname=u67440', $user, $pass,
      [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); // Заменить test на имя БД, совпадает с логином uXXXXX
  // Получение данных из формы
    $login = $_POST['login'];
    $password = $_POST['password'];
    
  // Подготовленный запрос для проверки логина и пароля
// Подготовка SQL-запроса
    $stmt = $db->prepare("SELECT * FROM test WHERE login = :login");
    $stmt->execute(array(':login' => $login));
    $user = $stmt->fetch();

    // Проверка наличия пользователя и совпадения пароля
    if (($user && password_verify($password, $user['password'])) || (($_COOKIE['login'] == $login) && ($_COOKIE['pass'] == $password))) {
        // Логин и пароль верные
        echo "Успешный вход!";
    } else {
        // Логин или пароль неверные
        echo "Ошибка: Неверный логин или пароль!";
        header('Location: ./');
        exit();
    }

  if (!$session_started) {
    session_start();
  }
  // Если все ок, то авторизуем пользователя.
  $_SESSION['login'] = $_POST['login'];
  // Записываем ID пользователя.
  $_SESSION['uid'] = 123;

  // Делаем перенаправление.
  header('Location: ./');
}
