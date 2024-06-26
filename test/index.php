<?php
/**
 * Реализовать возможность входа с паролем и логином с использованием
 * сессии для изменения отправленных данных в предыдущей задаче,
 * пароль и логин генерируются автоматически при первоначальной отправке формы.
 */

// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  // Массив для временного хранения сообщений пользователю.
  $messages = array();

  // В суперглобальном массиве $_COOKIE PHP хранит все имена и значения куки текущего запроса.
  // Выдаем сообщение об успешном сохранении.
  if (!empty($_COOKIE['save'])) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('save', '', 100000);
    setcookie('login', '', 100000);
    setcookie('pass', '', 100000);
    // Выводим сообщение пользователю.
 echo '<script>
                    document.addEventListener("DOMContentLoaded", function() {
                        var modal = document.createElement("div");
                        modal.innerHTML = "Спасибо, результаты сохранены.";
                        modal.style.position = "fixed";
                        modal.style.zIndex = "1";
                        modal.style.top = "0";
                        modal.style.left = "0";
                        modal.style.width = "100%";
                        modal.style.height = "100%";
                        modal.style.backgroundColor = "rgba(0, 0, 0, 0.5)";
                        modal.style.display = "flex";
                        modal.style.justifyContent = "center";
                        modal.style.alignItems = "center";
                        modal.style.color = "#fff";
                        modal.style.fontSize = "24px";
                        modal.style.cursor = "pointer";
                        modal.addEventListener("click", function() {
                            modal.style.display = "none";
                        });
                        document.body.appendChild(modal);
                    });
              </script>';
    // Если в куках есть пароль, то выводим сообщение.
    if (!empty($_COOKIE['pass'])) {
      $messages[] = sprintf('Вы можете <a href="login.php">войти</a> с логином <strong>%s</strong>
        и паролем <strong>%s</strong> для изменения данных.',
        strip_tags($_COOKIE['login']),
        strip_tags($_COOKIE['pass']));
    }
  }

  // Складываем признак ошибок в массив.
  $errors = array();
  $errors['fio'] = !empty($_COOKIE['fio_error']);
  $error = true;

  // TODO: аналогично все поля.

  // Выдаем сообщения об ошибках.
  if (!empty($errors['fio'])) {
    // Удаляем куку, указывая время устаревания в прошлом.
    $error = false;
    setcookie('fio_error', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="error">Заполните имя.</div>';
  }
  // TODO: тут выдать сообщения об ошибках в других полях.

  // Складываем предыдущие значения полей в массив, если есть.
  // При этом санитизуем все данные для безопасного отображения в браузере.
  $values = array();
  $values['fio'] = empty($_COOKIE['fio_value']) ? '' : strip_tags($_COOKIE['fio_value']);
  // TODO: аналогично все поля.

  // Если нет предыдущих ошибок ввода, есть кука сессии, начали сессию и
  // ранее в сессию записан факт успешного логина.
  if (session_start() && (!empty($_SESSION['login'])) && (!empty($_COOKIE[session_name()])) && $error) {
    // TODO: загрузить данные пользователя из БД
    // и заполнить переменную $values,
    // предварительно санитизовав.
 include('../db.php');
$db = new PDO('mysql:host=localhost;dbname=' . $db_name, $db_login, $db_pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); // Заменить test на имя БД, совпадает с логином uXXXXX
    $stmt = $db->prepare("SELECT name FROM test WHERE login = :login");
    $stmt->execute(['login' => $_SESSION['login']]);
    $row = $stmt->fetch();

    $values = [
        'fio' => htmlspecialchars($row['name'])
    ];
    setcookie('fio_value',$row['name'], time() + 30 * 24 * 60 * 60);

    printf('Вход с логином %s, uid %d', $_SESSION['login'], $_SESSION['uid']);
  }

  // Включаем содержимое файла form.php.
  // В нем будут доступны переменные $messages, $errors и $values для вывода 
  // сообщений, полей с ранее заполненными данными и признаками ошибок.
  include('form.php');
}
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.
else 
{
if ($_POST['button'] == "ok"){
  // Проверяем ошибки.
  $errors = FALSE;
  if (empty($_POST['fio'])) {
    // Выдаем куку на день с флажком об ошибке в поле fio.
    setcookie('fio_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
    // Сохраняем ранее введенное в форму значение на месяц.
    setcookie('fio_value', $_POST['fio'], time() + 30 * 24 * 60 * 60);

// *************
// TODO: тут необходимо проверить правильность заполнения всех остальных полей.
// Сохранить в Cookie признаки ошибок и значения полей.
// *************

  if ($errors) {
    // При наличии ошибок перезагружаем страницу и завершаем работу скрипта.
    header('Location: index.php');
    exit();
  }
  else {
    // Удаляем Cookies с признаками ошибок.
    setcookie('fio_error', '', 100000);
    // TODO: тут необходимо удалить остальные Cookies.
  }

  // Проверяем меняются ли ранее сохраненные данные или отправляются новые.
  if (!empty($_COOKIE[session_name()]) &&
      session_start() && !empty($_SESSION['login'])) {
    // TODO: перезаписать данные в БД новыми данными,
    // кроме логина и пароля.
   include('../db.php');
$db = new PDO('mysql:host=localhost;dbname=' . $db_name, $db_login, $db_pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); // Заменить test на имя БД, совпадает с логином uXXXXX
    $sql = "UPDATE test SET name = :name WHERE login = :login";
    
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':name', $_POST['fio']);
    $stmt->bindParam(':login', $_SESSION['login']);
    
    $stmt->execute();
  }
  else {
    // Генерируем уникальный логин и пароль.
    // TODO: сделать механизм генерации, например функциями rand(), uniquid(), md5(), substr().
    $login = 'user_' . uniqid(); // Генерация уникального логина
    $password = substr(md5(rand()), 0, 8); // Генерация уникального пароля
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    // Сохраняем в Cookies.
    setcookie('login', $login, time() + 24 * 60 * 60);
    setcookie('pass', $password, time() + 24 * 60 * 60);

    // TODO: Сохранение данных формы, логина и хеш md5() пароля в базу данных.
include('../db.php');
$db = new PDO('mysql:host=localhost;dbname=' . $db_name, $db_login, $db_pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); // Заменить test на имя БД, совпадает с логином uXXXXX

    // Подготовленный запрос. Не именованные метки.
    try {
      $stmt = $db->prepare("INSERT INTO test (login, password, name) VALUES (?, ?, ?)");
      $stmt->execute([$login, $hashedPassword, $_POST['fio']]);
    }
    catch(PDOException $e){
      print('Error : ' . $e->getMessage());
      exit();
    }

  }

  // Сохраняем куку с признаком успешного сохранения.
  setcookie('save', '1');

      header('Location: ./');
}
  else
{
    if ($_POST['button'] == "exit") {
    setcookie('logout', 'exit', time() + 24 * 60 * 60);
    setcookie('fio_value','', 100000);
          header('Location: login.php');
      exit();
}
  }
}
