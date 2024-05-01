<?php

/**
 * Задача 6. Реализовать вход администратора с использованием
 * HTTP-авторизации для просмотра и удаления результатов.
 **/

// Пример HTTP-аутентификации.
// PHP хранит логин и пароль в суперглобальном массиве $_SERVER.
// Подробнее см. стр. 26 и 99 в учебном пособии Веб-программирование и веб-сервисы.
if (empty($_SERVER['PHP_AUTH_USER']) ||
    empty($_SERVER['PHP_AUTH_PW']) ||
    $_SERVER['PHP_AUTH_USER'] != 'admin' ||
    md5($_SERVER['PHP_AUTH_PW']) != md5('123')) {
  header('HTTP/1.1 401 Unanthorized');
  header('WWW-Authenticate: Basic realm="My site"');
  print('<h1>401 Требуется авторизация</h1>');
  exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
echo 'Вы успешно авторизовались и видите защищенные паролем данные.'."<br>";

 include('../db.php');
$db = new PDO('mysql:host=localhost;dbname=' . $db_name, $db_login, $db_pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); // Заменить test на имя БД, совпадает с логином uXXXXX
try {
    $query = $db->query("SELECT a.userid, a.name, a.phone, a.email, a.data, a.pol, a.bio, a.ok, u.login, GROUP_CONCAT(DISTINCT l2.name SEPARATOR ', ') as languages
                        FROM application3 a
                        INNER JOIN users u ON a.userid = u.userid
                        LEFT JOIN ap_lan3 al3 ON a.userid = al3.userid
                        LEFT JOIN language2 l2 ON al3.id_language = l2.id
                        GROUP BY a.userid, a.name, a.phone, a.email, a.data, a.pol, a.bio, a.ok, u.login");
    
    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    // Вывод данных
    foreach ($results as $row) {
        echo "Пользователь с login" . $row['login'] ." и id ". $row['userid'] . "<br>";
        echo "Name: " . $row['name'] . "<br>";
        echo "Phone: " . $row['phone'] . "<br>";
        echo "Email: " . $row['email'] . "<br>";
        echo "Data: " . $row['data'] . "<br>";
        echo "Gender: " . $row['pol'] . "<br>";
        echo "Bio: " . $row['bio'] . "<br>";
        echo "Ok: " . $row['ok'] . "<br>";
        echo "Languages: " . $row['languages'] . "<br><br>";
    }

} catch (PDOException $e) {
    echo 'Ошибка: ' . $e->getMessage();
}
}
    
// *********
// Здесь нужно прочитать отправленные ранее пользователями данные и вывести в таблицу.
// Реализовать просмотр и удаление всех данных.
// *********
?>
    <form action = "">
            <input name="UserId"/>
          <input type="submit" name = "button" value="Delete" />
    </form>
<?php
else{
    if($_POST['button'] == "Delete")
    {
    $sql = "SELECT userid FROM users WHERE userid = $userid";
    $result = $db->query($sql);

if ($result->num_rows > 0) {
    // userid существует - выполняем операции удаления
    $sql_delete_application = "DELETE FROM application3 WHERE userid = $userid";
    $sql_delete_ap_lan = "DELETE FROM ap_lan3 WHERE userid = $userid";
    $sql_delete_users = "DELETE FROM users WHERE userid = $userid";

    // Выполнение операций удаления
    $db->query($sql_delete_application);
    $db->query($sql_delete_ap_lan);
    $db->query($sql_delete_users);

    echo "Данные успешно удалены.";
} else {
    echo "userid не найден в базе данных.";
}
    }
}
?>
