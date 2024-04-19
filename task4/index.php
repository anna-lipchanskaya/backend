<?php
/**
 * Реализовать проверку заполнения обязательных полей формы в предыдущей
 * с использованием Cookies, а также заполнение формы по умолчанию ранее
 * введенными значениями.
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
    // Если есть параметр save, то выводим сообщение пользователю.
    $messages[] = 'Спасибо, результаты сохранены.';
  }

  // Складываем признак ошибок в массив.
  $errors = array();
  $errors['name'] = !empty($_COOKIE['name_error']);
  $errors['name_len'] = !empty($_COOKIE['name_error_len']);
  $errors['name_struct'] = !empty($_COOKIE['name_error_struct']);
  $errors['phone'] = !empty($_COOKIE['phone_error']);
  $errors['phone_struct'] = !empty($_COOKIE['phone_error_struct']);
  $errors['phone_len'] = !empty($_COOKIE['phone_error_len']);
  // TODO: аналогично все поля.

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
  
  // Складываем предыдущие значения полей в массив, если есть.
  $values = array();
  $values['name'] = empty($_COOKIE['name_value']) ? '' : $_COOKIE['name_value'];
  $values['phone'] = empty($_COOKIE['phone_value']) ? '' : $_COOKIE['phone_value'];
  // TODO: аналогично все поля.

  // Включаем содержимое файла form.php.
  // В нем будут доступны переменные $messages, $errors и $values для вывода 
  // сообщений, полей с ранее заполненными данными и признаками ошибок.
  include('form.php');
}
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.
else {
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
  // Сохраняем ранее введенное в форму значение на месяц.
  setcookie('name_value', $_POST['name'], time() + 30 * 24 * 60 * 60);
  setcookie('phone_value', $_POST['phone'], time() + 30 * 24 * 60 * 60);

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
    setcookie('name_error', '', 100000);
    setcookie('name_error_len', '', 100000);
    setcookie('name_error_struct', '', 100000);
    setcookie('phone_error', '', 100000);
    setcookie('phone_error_len', '', 100000);
    setcookie('phone_error_struct', '', 100000);
    // TODO: тут необходимо удалить остальные Cookies.
  }

  // Сохранение в базу данных.
$user = 'u67440'; // Заменить на ваш логин uXXXXX
$pass = '7848123'; // Заменить на пароль, такой же, как от SSH
$db = new PDO('mysql:host=localhost;dbname=u67440', $user, $pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); // Заменить test на имя БД, совпадает с логином uXXXXX
// Подготовленный запрос. Не именованные метки.
try {
$stmt = $db->prepare("INSERT INTO application (name, phone, email, data, pol, bio, ok) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->execute([$_POST['name'], $_POST['phone'], $_POST['email'], $_POST['data'], $_POST['pol'], $_POST['bio'], $_POST['ok']]);
$lastId = $db->lastInsertId();

foreach ($_POST['abilities'] as $ability) {
    $stmtLang = $db->prepare("SELECT id FROM language WHERE name = ?");
    $stmtLang->execute([$ability]);
    $languageId = $stmtLang->fetchColumn();

    $stmtApLang = $db->prepare("INSERT INTO ap_lan (id_application, id_language) VALUES (:lastId, :languageId)");
    $stmtApLang->bindParam(':lastId', $lastId);
    $stmtApLang->bindParam(':languageId', $languageId);
    $stmtApLang->execute();
}
}
catch(PDOException $e){
  print('Error : ' . $e->getMessage());
  exit();
}

  // Сохраняем куку с признаком успешного сохранения.
  setcookie('save', '1');

  // Делаем перенаправление.
  header('Location: index.php');
}
