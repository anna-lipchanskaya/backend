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
                        modal.style.color = "#1c87c9";
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
}
if (empty($_POST['phone'])) {
  print('Запоните телефон.<br/>');
  $errors = TRUE;
}
if (empty($_POST['email'])) {
  print('Запоните почту.<br/>');
  $errors = TRUE;
}
if (empty($_POST['bio'])) {
  print('Запоните биографию.<br/>');
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
