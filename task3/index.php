<?php
// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!empty($_GET['save'])) {
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
    }

    include('form.php');
    exit();
}
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.

// Проверяем ошибки.
$errors = FALSE;
if (empty($_POST['name'])) {
    print('Заполните имя.<br/>');
    $errors = TRUE;
} elseif (!preg_match('/^[\p{L}\s]+$/u', $_POST['name'])) {
    print('Имя может содержать только буквы и пробелы.<br/>');
    $errors = TRUE;
} elseif (strlen($_POST['name']) > 150) {
    print('Имя '.$_POST['name'].' не должно превышать 150 символов.<br/>');
    $errors = TRUE;
}

if (empty($_POST['phone'])) {
    print('Заполните телефон.<br/>');
    $errors = TRUE;
} elseif (!preg_match('/^\d+$/', $_POST['phone'])) {
    print('Телефон должен состоять только из цифр.<br/>');
    $errors = TRUE;
}elseif (strlen($_POST['phone']) > 11) {
            print('Телефон '.$_POST['phone'].' не должно превышать 11 символов.<br/>');
            $errors = TRUE;
        }
if (empty($_POST['data'])) {
  print('Заполните дату.<br/>');
  $errors = TRUE;
} elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $_POST['data'])) {
  print('Дата должна быть в формате YYYY-MM-DD.<br/>');
  $errors = TRUE;
}
if (empty($_POST['email'])) {
    print('Заполните адрес электронной почты.<br/>');
    $errors = TRUE;
} elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    print('Введите корректный адрес электронной почты.<br/>');
    $errors = TRUE;
}elseif (strlen($_POST['email']) > 150) {
            print('Адрес электронной почты '.$_POST['email'].' не должен превышать 150 символов.<br/>');
            $errors = TRUE;
        }
if (empty($_POST['pol'])) {
    print('Заполните пол.<br/>');
    $errors = TRUE;
} elseif ($_POST['pol'] !== 'W' && $_POST['pol'] !== 'M') {
    print('Выберите только мужской или женский пол.<br/>');
    $errors = TRUE;
}

if (empty($_POST['ok'])) {
    print('Подтвердите соглашение.<br/>');
    $errors = TRUE;
} elseif ($_POST['ok'] !== 'on') {
    print('Подтвердите соглашение.<br/>');
    $errors = TRUE;
}
$allowed_languages = array("Pascal", "C", "C++", "JavaScript", "PHP", "Python", "Java", "Haskel");

if (empty($_POST['abilities'])) {
    print('Выберите хотя бы 1 язык программирования.<br/>');
    $errors = TRUE;
} else {
    foreach ($_POST['abilities'] as $language) {
        if (!in_array($language, $allowed_languages)) {
            print('Выберите только представленные языки.<br/>');
            $errors = TRUE;
            break;
        }
    }
}
if (empty($_POST['bio'])) {
  print('Запоните биографию.<br/>');
  $errors = TRUE;
}elseif (strlen($_POST['bio']) > 300) {
            print('Биография '.$_POST['bio'].' не должна превышать 300 символов.<br/>');
            $errors = TRUE;
        }
if (empty($_POST['abilities'])) {
    print('Выберите хотя бы 1 язык программирования.<br/>');
    $errors = TRUE;
}



// *************
// Тут необходимо проверить правильность заполнения всех остальных полей.
// *************

if ($errors) {
  // При наличии ошибок завершаем работу скрипта.
  exit();
}

// Сохранение в базу данных.
include('../db.php');
$db = new PDO('mysql:host=localhost;dbname=' . $db_name, $db_login, $db_pass,
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

//  stmt - это "дескриптор состояния".
 
//  Именованные метки.
//$stmt = $db->prepare("INSERT INTO test (label,color) VALUES (:label,:color)");
//$stmt -> execute(['label'=>'perfect', 'color'=>'green']);
 
//Еще вариант
/*$stmt = $db->prepare("INSERT INTO users (firstname, lastname, email) VALUES (:firstname, :lastname, :email)");
$stmt->bindParam(':firstname', $firstname);
$stmt->bindParam(':lastname', $lastname);
$stmt->bindParam(':email', $email);
$firstname = "John";
$lastname = "Smith";
$email = "john@test.com";
$stmt->execute();
*/

// Делаем перенаправление.
// Если запись не сохраняется, но ошибок не видно, то можно закомментировать эту строку чтобы увидеть ошибку.
// Если ошибок при этом не видно, то необходимо настроить параметр display_errors для PHP.
header('Location: ?save=1');
