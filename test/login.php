<html>
  <head>
    <style>
/* Сообщения об ошибках и поля с ошибками выводим с красным бордюром. */
.error {
  border: 2px solid red;
}
    </style>
  </head>
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
        }

        // Делаем перенаправление на форму.
        header('Location: ./');
        exit();
    }
}

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $errors = array();
      $messages = array();
  $errors['error'] = !empty($_COOKIE['error']);

  // TODO: аналогично все поля.

  // Выдаем сообщения об ошибках.
  if (!empty($errors['error'])) {
    setcookie('error', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="error">Неправильный логин или пароль</div>';
  }
  // TODO: тут выдать сообщения об ошибках в других полях.
  // Складываем предыдущие значения полей в массив, если есть.
  // При этом санитизуем все данные для безопасного отображения в браузере.
  $values = array();
  $values['login'] = empty($_COOKIE['login_value']) ? '' : strip_tags($_COOKIE['login_value']);
  $values['password'] = empty($_COOKIE['password_value']) ? '' : strip_tags($_COOKIE['password_value']);
if (!empty($messages)) {
  print('<div id="messages">');
  // Выводим все сообщения.
  foreach ($messages as $message) {
    print($message);
  }
  print('</div>');
}

// Далее выводим форму отмечая элементы с ошибками классом error
// и задавая начальные значения элементов ранее сохраненными.
?>
<body>
<form action="" method="post">
  <input name="login" <?php if ($errors['error']) {print 'class="error"';} ?> value="<?php print $values['login']; ?>"/>
  <input name="password" <?php if ($errors['error']) {print 'class="error"';} ?> value="<?php print $values['password']; ?>"/>
  <input type="submit" value="Войти" />
</form>
  </body>
</html>

<?php
}
else
{
// Иначе, если запрос был методом POST, т.е. нужно сделать авторизацию с записью логина в сессию.

  // TODO: Проверть есть ли такой логин и пароль в базе данных.
  // Выдать сообщение об ошибках.

    include('../db.php');
$db = new PDO('mysql:host=localhost;dbname=' . $db_name, $db_login, $db_pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); // Заменить test на имя БД, совпадает с логином uXXXXX
  // Получение данных из формы
    $login = $_POST['login'];
    $password = $_POST['password'];
    
  // Подготовленный запрос для проверки логина и пароля
// Подготовка SQL-запроса
    $stmt = $db->prepare("SELECT * FROM test WHERE login = :login");
    $stmt->execute(array(':login' => $login));
    $use = $stmt->fetch();

      // Сохраняем ранее введенное в форму значение на месяц.
    setcookie('login_value', $_POST['login'], time() + 30 * 24 * 60 * 60);
    setcookie('password_value', $_POST['password'], time() + 30 * 24 * 60 * 60);
  
    // Проверка наличия пользователя и совпадения пароля
    if ($use && password_verify($password, $use['password'])) {
        // Логин и пароль верные
   setcookie('login_value', '', 100000);
    setcookie('password_value', '', 100000);
    } else {
        // Логин или пароль неверные
     setcookie('error', '1', time() + 24 * 60 * 60);
      header('Location: login.php');
        exit();
    }
  if (!$session_started) {
    session_start();
  }
  // Если все ок, то авторизуем пользователя.
  $_SESSION['login'] = $_POST['login'];
  // Записываем ID пользователя.
  $_SESSION['uid'] = rand(1, 100);

  // Делаем перенаправление.
  header('Location: ./');
}
