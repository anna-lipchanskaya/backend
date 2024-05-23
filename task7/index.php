<?php
require_once('db.php');  
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
    setcookie('save', '', 100000, '/', '', false, true);
    setcookie('login', '', 100000, '/', '', false, true);
    setcookie('pass', '', 100000, '/', '', false, true);
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
      $messages[] = sprintf('Вы можете <a href="login.php" class="login-link">войти</a> с логином <strong class="login">%s</strong>
        и паролем <strong class="password">%s</strong> для изменения данных.',
        strip_tags($_COOKIE['login']),
        strip_tags($_COOKIE['pass']));
    }
  }

  // Складываем признак ошибок в массив.
$errors = array();
  $errors['name'] = !empty($_COOKIE['name_error']);
  $errors['name_len'] = !empty($_COOKIE['name_error_len']);
  $errors['name_struct'] = !empty($_COOKIE['name_error_struct']);
  $errors['phone'] = !empty($_COOKIE['phone_error']);
  $errors['phone_struct'] = !empty($_COOKIE['phone_error_struct']);
  $errors['phone_len'] = !empty($_COOKIE['phone_error_len']);
  $errors['email'] = !empty($_COOKIE['email_error']);
  $errors['email_struct'] = !empty($_COOKIE['email_error_struct']);
  $errors['email_len'] = !empty($_COOKIE['email_error_len']);
  $errors['data'] = !empty($_COOKIE['data_error']);
  $errors['data_struct'] = !empty($_COOKIE['data_error_struct']);
  $errors['pol'] = !empty($_COOKIE['pol_error']);
  $errors['pol_struct'] = !empty($_COOKIE['pol_error_struct']);
  $errors['ok'] = !empty($_COOKIE['ok_error']);
  $errors['abilities'] = !empty($_COOKIE['abilities_error']);
  $errors['abilities_struct'] = !empty($_COOKIE['abilities_error_struct']);
  $errors['bio'] = !empty($_COOKIE['bio_error']);
  $errors['bio_len'] = !empty($_COOKIE['bio_error_len']);
  // TODO: аналогично все поля.
  $error = true;

  // TODO: аналогично все поля.

  // Выдаем сообщения об ошибках.
 // Выдаем сообщения об ошибках.
  if ($errors['name']) {
    // Удаляем куки, указывая время устаревания в прошлом.
    setcookie('name_error', '', 100000, '/', '', false, true);
    setcookie('name_value', '', 100000, '/', '', false, true);
    // Выводим сообщение.
    $messages[] = '<div class="error">Заполните имя.</div>';
  }
  if($errors['name_len']) {
    setcookie('name_error_len', '', 100000, '/', '', false, true);
    setcookie('name_value', '', 100000, '/', '', false, true);
    $messages[] = '<div class="error">Имя должно содержать только буквы.</div>';
  }
    if($errors['name_struct']) {
    setcookie('name_error_struct', '', 100000, '/', '', false, true);
    setcookie('name_value', '', 100000, '/', '', false, true);
    $messages[] = '<div class="error">Имя должно быть не длиннее 150 слов.</div>';
  }
    if ($errors['phone']) {
    // Удаляем куки, указывая время устаревания в прошлом.
    setcookie('phone_error', '', 100000, '/', '', false, true);
    setcookie('phone_value', '', 100000, '/', '', false, true);
    // Выводим сообщение.
    $messages[] = '<div class="error">Заполните телефон.</div>';
  }
  if($errors['phone_len']) {
    setcookie('phone_error_len', '', 100000, '/', '', false, true);
    setcookie('phone_value', '', 100000, '/', '', false, true);
    $messages[] = '<div class="error">Телефон не должно превышать 11 символов.</div>';
  }
    if($errors['phone_struct']) {
    setcookie('phone_error_struct', '', 100000, '/', '', false, true);
    setcookie('phone_value', '', 100000, '/', '', false, true);
    $messages[] = '<div class="error">Телефон должен состоять только из цифр.</div>';
  }
    if ($errors['email']) {
    // Удаляем куки, указывая время устаревания в прошлом.
    setcookie('email_error', '', 100000, '/', '', false, true);
    setcookie('email_value', '', 100000, '/', '', false, true);
    // Выводим сообщение.
    $messages[] = '<div class="error">Заполните адрес электронной почты.</div>';
  }
  if($errors['email_len']) {
    setcookie('email_error_len', '', 100000, '/', '', false, true);
    setcookie('email_value', '', 100000, '/', '', false, true);
    $messages[] = '<div class="error">Адрес электронной почты не должен превышать 150 символов.</div>';
  }
    if($errors['email_struct']) {
    setcookie('email_error_struct', '', 100000, '/', '', false, true);
    setcookie('email_value', '', 100000, '/', '', false, true);
    $messages[] = '<div class="error">Введите корректный адрес электронной почты.</div>';
  }
      if ($errors['data']) {
    // Удаляем куки, указывая время устаревания в прошлом.
    setcookie('data_error', '', 100000, '/', '', false, true);
    setcookie('data_value', '', 100000, '/', '', false, true);
    // Выводим сообщение.
    $messages[] = '<div class="error">Заполните дату.</div>';
  }
  if($errors['data_struct']) {
    setcookie('data_error_struct', '', 100000, '/', '', false, true);
    setcookie('data_value', '', 100000, '/', '', false, true);
    $messages[] = '<div class="error">Дата должна быть в формате YYYY-MM-DD.</div>';
  }
        if ($errors['ok']) {
    // Удаляем куки, указывая время устаревания в прошлом.
    setcookie('ok_error', '', 100000, '/', '', false, true);
    setcookie('ok_value', '', 100000, '/', '', false, true);
    // Выводим сообщение.
    $messages[] = '<div class="error">Подтвердите соглашение.</div>';
  }
        if ($errors['pol']) {
    // Удаляем куки, указывая время устаревания в прошлом.
    setcookie('pol_error', '', 100000, '/', '', false, true);
    setcookie('pol_value', '', 100000, '/', '', false, true);
    // Выводим сообщение.
    $messages[] = '<div class="error">Заполните пол.</div>';
  }
  if($errors['pol_struct']) {
    setcookie('pol_error_struct', '', 100000, '/', '', false, true);
    setcookie('pol_value', '', 100000, '/', '', false, true);
    $messages[] = '<div class="error">Выберите только мужской или женский пол.</div>';
  }
    if ($errors['abilities']) {
    // Удаляем куки, указывая время устаревания в прошлом.
    setcookie('abilities_error', '', 100000, '/', '', false, true);
    setcookie('abilities_value', '', 100000, '/', '', false, true);
    // Выводим сообщение.
    $messages[] = '<div class="error">Выберите хотя бы 1 язык программирования.</div>';
  }
  if($errors['abilities_struct']) {
    setcookie('abilities_error_struct', '', 100000, '/', '', false, true);
    setcookie('abilities_value', '', 100000, '/', '', false, true);
    $messages[] = '<div class="error">Выберите только представленные языки.</div>';
  }
      if ($errors['bio']) {
    // Удаляем куки, указывая время устаревания в прошлом.
    setcookie('bio_error', '', 100000, '/', '', false, true);
    setcookie('bio_value', '', 100000, '/', '', false, true);
    // Выводим сообщение.
    $messages[] = '<div class="error">Запоните биографию.</div>';
  }
  if($errors['bio_len']) {
    setcookie('bio_error_len', '', 100000, '/', '', false, true);
    setcookie('bio_value', '', 100000, '/', '', false, true);
    $messages[] = '<div class="error">Биография не должна превышать 300 символов.</div>';
  }
  // TODO: тут выдать сообщения об ошибках в других полях.

  // Складываем предыдущие значения полей в массив, если есть.
  // При этом санитизуем все данные для безопасного отображения в браузере.
 $values = array();
  $values['name'] = empty($_COOKIE['name_value']) ? '' : $_COOKIE['name_value'];
  $values['phone'] = empty($_COOKIE['phone_value']) ? '' : $_COOKIE['phone_value'];
  $values['email'] = empty($_COOKIE['email_value']) ? '' : $_COOKIE['email_value'];
  $values['data'] = empty($_COOKIE['data_value']) ? '' : $_COOKIE['data_value'];
  $values['pol'] = empty($_COOKIE['pol_value']) ? '' : $_COOKIE['pol_value'];
 $values['abilities'] = isset($_COOKIE['abilities_value']) ? unserialize($_COOKIE['abilities_value']) : [];
  $values['bio'] = empty($_COOKIE['bio_value']) ? '' : $_COOKIE['bio_value'];
  $values['ok'] = empty($_COOKIE['ok_value']) ? '' : $_COOKIE['ok_value'];
  // TODO: аналогично все поля.

  // Если нет предыдущих ошибок ввода, есть кука сессии, начали сессию и
  // ранее в сессию записан факт успешного логина.
  if (session_start() && (!empty($_SESSION['login'])) && (!empty($_COOKIE[session_name()])) && $error) {
    // TODO: загрузить данные пользователя из БД
    // и заполнить переменную $values,
    // предварительно санитизовав.
    $data = db_get_Pass_Login_user($_SESSION['login']);
    
$rows = db_get_Languages($data['userid']);
$languages = [];
foreach ($rows as $row) {
    $languages[] = htmlspecialchars($row['name']);
}

// Сериализуем массив перед передачей в куки
$abilities_serialized = serialize($languages);
    $row = db_get_form_user($data['userid']);

    $values = [
        'name' => htmlspecialchars($row['name']),
        'phone' => htmlspecialchars($row['phone']),
        'email' => htmlspecialchars($row['email']),
        'data' => htmlspecialchars($row['data']),
        'pol' => htmlspecialchars($row['pol']),
        'bio' => htmlspecialchars($row['bio']),
        'ok' => htmlspecialchars($row['ok']),
        'abilities' => $languages
    ];

    
    setcookie('name_value',htmlspecialchars($row['name']), time() + 30 * 24 * 60 * 60, '/', '', false, true);
    setcookie('phone_value',htmlspecialchars($row['phone']), time() + 30 * 24 * 60 * 60, '/', '', false, true);
    setcookie('email_value',htmlspecialchars($row['email']), time() + 30 * 24 * 60 * 60, '/', '', false, true);
    setcookie('data_value',htmlspecialchars($row['data']), time() + 30 * 24 * 60 * 60, '/', '', false, true);
    setcookie('pol_value',htmlspecialchars($row['pol']), time() + 30 * 24 * 60 * 60, '/', '', false, true);
    setcookie('bio_value',htmlspecialchars($row['bio']), time() + 30 * 24 * 60 * 60, '/', '', false, true);
    setcookie('ok_value',htmlspecialchars($row['ok']), time() + 30 * 24 * 60 * 60, '/', '', false, true);
    setcookie('abilities_value', $abilities_serialized, time() + 30 * 24 * 60 * 60, '/', '', false, true);
 $messages[] = sprintf('Вход с логином <strong class="login">%s</strong>
        и id <strong class="login">%d</strong>.',
        $_SESSION['login'],
        $_SESSION['uid']);

  }
  else{
          if (!empty($_SERVER['PHP_AUTH_USER']) ||
    !empty($_SERVER['PHP_AUTH_PW']))
  {
    header('Location: admin.php');
    exit();
  }
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
  // Сохраняем ранее введенное в форму значение на год.
  setcookie('name_value', $_POST['name'], time() + 365 * 24 * 60 * 60, '/', '', false, true);
  setcookie('phone_value', $_POST['phone'], time() + 365 * 24 * 60 * 60, '/', '', false, true);
  setcookie('email_value', $_POST['email'], time() + 365 * 24 * 60 * 60, '/', '', false, true);
  setcookie('data_value', $_POST['data'], time() + 365 * 24 * 60 * 60, '/', '', false, true);
  setcookie('pol_value', $_POST['pol'], time() + 365 * 24 * 60 * 60, '/', '', false, true);
  setcookie('abilities_value', serialize($_POST['abilities']), time() + 365 * 24 * 60 * 60, '/', '', false, true);
  setcookie('bio_value', $_POST['bio'], time() + 365 * 24 * 60 * 60, '/', '', false, true);
  setcookie('ok_value', $_POST['ok'], time() + 365 * 24 * 60 * 60, '/', '', false, true);


  // Проверяем меняются ли ранее сохраненные данные или отправляются новые.
  if (!empty($_COOKIE[session_name()]) &&
      session_start() && !empty($_SESSION['login'])) {
    // TODO: перезаписать данные в БД новыми данными,
    // кроме логина и пароля.
    if (isset($_POST['submit_test'])) {
    if (isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] === @$_POST['csrf_token']) {
          $userid = db_get_Pass_Login_user($_SESSION['login'])['userid'];
  }
      else {
        echo "Token неверный";
    }
  }
  }
  if (empty($_COOKIE[session_name()]) ||
      !session_start() || empty($_SESSION['login'])) {
    $userid = -1;
  }
   $result = db_set_application($userid, $_POST['name'], $_POST['phone'], $_POST['email'], $_POST['data'], $_POST['pol'], $_POST['bio'], $_POST['ok'], $_POST['abilities']);
  if($result == FALSE)
        {
          echo "Error";
          exit();
        }
    if ($result === "Error") {
    // При наличии ошибок перезагружаем страницу и завершаем работу скрипта.
    header('Location: index.php');
    exit();
  }
     else {
    // Удаляем Cookies с признаками ошибок.
    setcookie('name_error', '', 100000, '/', '', false, true);
    setcookie('name_error_len', '', 100000, '/', '', false, true);
    setcookie('name_error_struct', '', 100000, '/', '', false, true);
    setcookie('phone_error', '', 100000, '/', '', false, true);
    setcookie('phone_error_len', '', 100000, '/', '', false, true);
    setcookie('phone_error_struct', '', 100000, '/', '', false, true);
    setcookie('email_error', '', 100000, '/', '', false, true);
    setcookie('email_error_len', '', 100000, '/', '', false, true);
    setcookie('email_error_struct', '', 100000, '/', '', false, true);
    setcookie('data_error', '', 100000, '/', '', false, true);
    setcookie('data_error_struct', '', 100000, '/', '', false, true);
    setcookie('pol_error', '', 100000, '/', '', false, true);
    setcookie('pol_error_struct', '', 100000, '/', '', false, true);
    setcookie('abilities_error', '', 100000, '/', '', false, true);
    setcookie('abilities_error_struct', '', 100000, '/', '', false, true);
    setcookie('bio_error', '', 100000, '/', '', false, true);
    setcookie('bio_error_len', '', 100000, '/', '', false, true);
    setcookie('ok_error', '', 100000, '/', '', false, true);
    // TODO: тут необходимо удалить остальные Cookies.
  }

  // Сохраняем куку с признаком успешного сохранения.
  setcookie('save', '1');
      header('Location: ./');
}
  else
{
    if ($_POST['button'] == "exit") {
    setcookie('logout', 'exit', time() + 24 * 60 * 60, '/', '', false, true);
setcookie('name_value', '', 100000, '/', '', false, true);
  setcookie('phone_value', '', 100000, '/', '', false, true);
  setcookie('email_value', '', 100000, '/', '', false, true);
  setcookie('data_value','', 100000, '/', '', false, true);
  setcookie('pol_value', '', 100000, '/', '', false, true);
  setcookie('abilities_value', '', 100000, '/', '', false, true);
  setcookie('bio_value', '', 100000, '/', '', false, true);
  setcookie('ok_value', '', 100000, '/', '', false, true);
  header('Location: login.php');
      exit();
}
  }
}
