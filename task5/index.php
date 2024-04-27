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
    setcookie('name_error', '', 100000);
    setcookie('name_value', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="error">Заполните имя.</div>';
  }
  if($errors['name_len']) {
    setcookie('name_error_len', '', 100000);
    setcookie('name_value', '', 100000);
    $messages[] = '<div class="error">Имя должно содержать только буквы.</div>';
  }
    if($errors['name_struct']) {
    setcookie('name_error_struct', '', 100000);
    setcookie('name_value', '', 100000);
    $messages[] = '<div class="error">Имя должно быть не длиннее 150 слов.</div>';
  }
    if ($errors['phone']) {
    // Удаляем куки, указывая время устаревания в прошлом.
    setcookie('phone_error', '', 100000);
    setcookie('phone_value', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="error">Заполните телефон.</div>';
  }
  if($errors['phone_len']) {
    setcookie('phone_error_len', '', 100000);
    setcookie('phone_value', '', 100000);
    $messages[] = '<div class="error">Телефон не должно превышать 11 символов.</div>';
  }
    if($errors['phone_struct']) {
    setcookie('phone_error_struct', '', 100000);
    setcookie('phone_value', '', 100000);
    $messages[] = '<div class="error">Телефон должен состоять только из цифр.</div>';
  }
    if ($errors['email']) {
    // Удаляем куки, указывая время устаревания в прошлом.
    setcookie('email_error', '', 100000);
    setcookie('email_value', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="error">Заполните адрес электронной почты.</div>';
  }
  if($errors['email_len']) {
    setcookie('email_error_len', '', 100000);
    setcookie('email_value', '', 100000);
    $messages[] = '<div class="error">Адрес электронной почты не должен превышать 150 символов.</div>';
  }
    if($errors['email_struct']) {
    setcookie('email_error_struct', '', 100000);
    setcookie('email_value', '', 100000);
    $messages[] = '<div class="error">Введите корректный адрес электронной почты.</div>';
  }
      if ($errors['data']) {
    // Удаляем куки, указывая время устаревания в прошлом.
    setcookie('data_error', '', 100000);
    setcookie('data_value', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="error">Заполните дату.</div>';
  }
  if($errors['data_struct']) {
    setcookie('data_error_struct', '', 100000);
    setcookie('data_value', '', 100000);
    $messages[] = '<div class="error">Дата должна быть в формате YYYY-MM-DD.</div>';
  }
        if ($errors['ok']) {
    // Удаляем куки, указывая время устаревания в прошлом.
    setcookie('ok_error', '', 100000);
    setcookie('ok_value', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="error">Подтвердите соглашение.</div>';
  }
        if ($errors['pol']) {
    // Удаляем куки, указывая время устаревания в прошлом.
    setcookie('pol_error', '', 100000);
    setcookie('pol_value', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="error">Заполните пол.</div>';
  }
  if($errors['pol_struct']) {
    setcookie('pol_error_struct', '', 100000);
    setcookie('pol_value', '', 100000);
    $messages[] = '<div class="error">Выберите только мужской или женский пол.</div>';
  }
    if ($errors['abilities']) {
    // Удаляем куки, указывая время устаревания в прошлом.
    setcookie('abilities_error', '', 100000);
    setcookie('abilities_value', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="error">Выберите хотя бы 1 язык программирования.</div>';
  }
  if($errors['abilities_struct']) {
    setcookie('abilities_error_struct', '', 100000);
    setcookie('abilities_value', '', 100000);
    $messages[] = '<div class="error">Выберите только представленные языки.</div>';
  }
      if ($errors['bio']) {
    // Удаляем куки, указывая время устаревания в прошлом.
    setcookie('bio_error', '', 100000);
    setcookie('bio_value', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="error">Запоните биографию.</div>';
  }
  if($errors['bio_len']) {
    setcookie('bio_error_len', '', 100000);
    setcookie('bio_value', '', 100000);
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
 include('../db.php');
$db = new PDO('mysql:host=localhost;dbname=' . $db_name, $db_login, $db_pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); // Заменить test на имя БД, совпадает с логином uXXXXX
    $stmt = $db->prepare("SELECT name, phone, email, data, pol, bio, ok  FROM application2 WHERE login = :login");
    $stmt->execute(['login' => $_SESSION['login']]);
    $row = $stmt->fetch();

    $values = [
        'name' => htmlspecialchars($row['name']),
        'phone' => htmlspecialchars($row['phone']),
        'email' => htmlspecialchars($row['email']),
        'data' => htmlspecialchars($row['data']),
        'pol' => htmlspecialchars($row['pol']),
        'bio' => htmlspecialchars($row['bio']),
        'ok' => htmlspecialchars($row['ok']),
    ];
    setcookie('name_value',$row['name'], time() + 30 * 24 * 60 * 60);
    setcookie('phone_value',$row['phone'], time() + 30 * 24 * 60 * 60);
    setcookie('email_value',$row['email'], time() + 30 * 24 * 60 * 60);
    setcookie('data_value',$row['data'], time() + 30 * 24 * 60 * 60);
    setcookie('pol_value',$row['pol'], time() + 30 * 24 * 60 * 60);
    setcookie('bio_value',$row['bio'], time() + 30 * 24 * 60 * 60);
    setcookie('ok_value',$row['ok'], time() + 30 * 24 * 60 * 60);
    
$stmt = $db->prepare("SELECT language.name 
    FROM ap_lan2 
    JOIN language ON ap_lan2.id_language = id 
    WHERE ap_lan2.login = :login");
$stmt->execute(['login' => $_SESSION['login']]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$values = [];
foreach ($rows as $row) {
    $values['abilities'][] = htmlspecialchars($row['name']);
}

// Сериализуем массив перед передачей в куки
$abilities_serialized = serialize($values['abilities']);

// Устанавливаем куки с сериализованным значением
setcookie('abilities_value', $abilities_serialized, time() + 30 * 24 * 60 * 60);
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
  if (empty($_POST['name'])) {
    // Выдаем куку на день с флажком об ошибке в поле name.
    setcookie('name_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }elseif (!preg_match('/^[\p{L}\s]+$/u', $_POST['name'])) {
    setcookie('name_error_len', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
} elseif (strlen($_POST['name']) > 150) {
    setcookie('name_error_struct', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
}
  if (empty($_POST['phone'])) {
    setcookie('phone_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
} elseif (!preg_match('/^\d+$/', $_POST['phone'])) {
    setcookie('phone_error_struct', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
}elseif (strlen($_POST['phone']) > 11) {
    setcookie('phone_error_len', '1', time() + 24 * 60 * 60);
            $errors = TRUE;
        }
  if (empty($_POST['email'])) {
    setcookie('email_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
} elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    setcookie('email_error_struct', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
}elseif (strlen($_POST['email']) > 150) {
    setcookie('email_error_len', '1', time() + 24 * 60 * 60);
            $errors = TRUE;
        }
if (empty($_POST['pol'])) {
    setcookie('pol_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
} elseif ($_POST['pol'] !== 'W' && $_POST['pol'] !== 'M') {
    setcookie('pol_error_struct', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
}
if ($_POST['ok'] !== 'on') {
    setcookie('ok_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
}
  if (empty($_POST['data'])) {
    setcookie('data_error', '1', time() + 24 * 60 * 60);
  $errors = TRUE;
} elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $_POST['data'])) {
    setcookie('data_error_struct', '1', time() + 24 * 60 * 60);
  $errors = TRUE;
}
$allowed_languages = array("Pascal", "C", "C++", "JavaScript", "PHP", "Python", "Java", "Haskel");

if (empty($_POST['abilities'])) {
    setcookie('abilities_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
} else {
    foreach ($_POST['abilities'] as $language) {
        if (!in_array($language, $allowed_languages)) {
    setcookie('abilities_error_struct', '1', time() + 24 * 60 * 60);
            $errors = TRUE;
            break;
        }
    }
}
if (empty($_POST['bio'])) {
    setcookie('bio_error', '1', time() + 24 * 60 * 60);
  $errors = TRUE;
}elseif (strlen($_POST['bio']) > 300) {
    setcookie('bio_error_len', '1', time() + 24 * 60 * 60);
            $errors = TRUE;
        }
  // Сохраняем ранее введенное в форму значение на год.
  setcookie('name_value', $_POST['name'], time() + 365 * 24 * 60 * 60);
  setcookie('phone_value', $_POST['phone'], time() + 365 * 24 * 60 * 60);
  setcookie('email_value', $_POST['email'], time() + 365 * 24 * 60 * 60);
  setcookie('data_value', $_POST['data'], time() + 365 * 24 * 60 * 60);
  setcookie('pol_value', $_POST['pol'], time() + 365 * 24 * 60 * 60);
  setcookie('abilities_value', serialize($_POST['abilities']), time() + 365 * 24 * 60 * 60);
  setcookie('bio_value', $_POST['bio'], time() + 365 * 24 * 60 * 60);
  setcookie('ok_value', $_POST['ok'], time() + 365 * 24 * 60 * 60);

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
    // Удаляем Cookies с признаками ошибок.
    setcookie('name_error', '', 100000);
    setcookie('name_error_len', '', 100000);
    setcookie('name_error_struct', '', 100000);
    setcookie('phone_error', '', 100000);
    setcookie('phone_error_len', '', 100000);
    setcookie('phone_error_struct', '', 100000);
    setcookie('email_error', '', 100000);
    setcookie('email_error_len', '', 100000);
    setcookie('email_error_struct', '', 100000);
    setcookie('data_error', '', 100000);
    setcookie('data_error_struct', '', 100000);
    setcookie('pol_error', '', 100000);
    setcookie('pol_error_struct', '', 100000);
    setcookie('abilities_error', '', 100000);
    setcookie('abilities_error_struct', '', 100000);
    setcookie('bio_error', '', 100000);
    setcookie('bio_error_len', '', 100000);
    setcookie('ok_error', '', 100000);
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
    $sql = "UPDATE application2 SET name = :name, phone = :phone, email = :email,  data = :data, pol = :pol, bio = :bio, ok = :ok WHERE login = :login";
    
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':name', $_POST['name']);
    $stmt->bindParam(':phone', $_POST['phone']);
    $stmt->bindParam(':email', $_POST['email']);
    $stmt->bindParam(':data', $_POST['data']);
    $stmt->bindParam(':pol', $_POST['pol']);
    $stmt->bindParam(':bio', $_POST['bio']);
    $stmt->bindParam(':ok', $_POST['ok']);
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
      $stmt = $db->prepare("INSERT INTO application2 (login, password, name, phone, email, data, pol, bio, ok) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $stmt->execute([$login, $hashedPassword, $_POST['name'], $_POST['phone'], $_POST['email'], $_POST['data'], $_POST['pol'], $_POST['bio'], $_POST['ok']]);
      $lastId = $db->lastInsertId();
      foreach ($_POST['abilities'] as $ability) {
    $stmtLang = $db->prepare("SELECT id FROM language WHERE name = ?");
    $stmtLang->execute([$ability]);
    $languageId = $stmtLang->fetchColumn();

    $stmtApLang = $db->prepare("INSERT INTO ap_lan2 (id_application, id_language, login) VALUES (:lastId, :languageId, :Login)");
    $stmtApLang->bindParam(':lastId', $lastId);
    $stmtApLang->bindParam(':languageId', $languageId);
    $stmtApLang->bindParam(':Login', $login);
    $stmtApLang->execute();
}
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
setcookie('name_value', '', 100000);
  setcookie('phone_value', '', 100000);
  setcookie('email_value', '', 100000);
  setcookie('data_value','', 100000);
  setcookie('pol_value', '', 100000);
  setcookie('abilities_value', '', 100000);
  setcookie('bio_value', '', 100000);
  setcookie('ok_value', '', 100000);
          header('Location: login.php');
      exit();
}
  }
}
