<?php
/**
 * Задача 6. Реализовать вход администратора с использованием
 * HTTP-авторизации для просмотра и удаления результатов.
 **/
require_once('auth.php');   
echo 'Вы успешно авторизовались и видите защищенные паролем данные.'."<br>";
require_once('db.php');
$query = "SELECT a.userid, a.name, a.phone, a.email, a.data, a.pol, a.bio, a.ok, u.login, GROUP_CONCAT(DISTINCT l2.name SEPARATOR ', ') as languages
                        FROM application3 a
                        INNER JOIN users u ON a.userid = u.userid
                        LEFT JOIN ap_lan3 al3 ON a.userid = al3.userid
                        LEFT JOIN language2 l2 ON al3.id_language = l2.id
                        GROUP BY a.userid, a.name, a.phone, a.email, a.data, a.pol, a.bio, a.ok, u.login";
    
    $results = executeQuery($query);

    // Вывод данных
    foreach ($results as $row) {
        echo "Пользователь с login " . $row['login'] ." и id ". $row['userid'] . "<br>";
        echo "Name: " . $row['name'] . "<br>";
        echo "Phone: " . $row['phone'] . "<br>";
        echo "Email: " . $row['email'] . "<br>";
        echo "Data: " . $row['data'] . "<br>";
        echo "Gender: " . $row['pol'] . "<br>";
        echo "Bio: " . $row['bio'] . "<br>";
        echo "Ok: " . $row['ok'] . "<br>";
        echo "Languages: " . $row['languages'] . "<br><br>";
    }
try{
    echo "Статистика языков " . "<br>";
    $stmt = $db->query("SELECT l2.name, count(*) AS count_users
            FROM application3 a 
            INNER JOIN ap_lan3 al3 ON a.userid = al3.userid
            INNER JOIN language2 l2 ON al3.id_language = l2.id
            GROUP BY l2.name");

    // Вывод результатов
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "{$row['name']} язык любят: {$row['count_users']} пользователя <br>";
    }

} catch(PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
}
    
// *********
// Здесь нужно прочитать отправленные ранее пользователями данные и вывести в таблицу.
// Реализовать просмотр и удаление всех данных.
// *********
?>
    <form action="" method="POST">
            <input name="delete"/>
          <input type="submit" name = "button" value="Delete" />
        <br>
            <input name="update"/>
          <input type="submit" name = "button" value="Update" />
    </form>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
     include('../db.php');
$db = new PDO('mysql:host=localhost;dbname=' . $db_name, $db_login, $db_pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); // Заменить test на имя БД, совпадает с логином uXXXXX
    if($_POST['button'] == "Delete")
    {
        if(!empty($_POST['delete']))
    {
    $userid = $_POST['delete'];
    $result = $db->query("SELECT userid FROM users WHERE userid = $userid");

if ($result->rowCount() > 0) {
    // userid существует - выполняем операции удаления
    $sql_delete_application = "DELETE FROM application3 WHERE userid = $userid";
    $sql_delete_ap_lan = "DELETE FROM ap_lan3 WHERE userid = $userid";
    $sql_delete_users = "DELETE FROM users WHERE userid = $userid";

    // Выполнение операций удаления
    $db->query($sql_delete_ap_lan);
    $db->query($sql_delete_users);
    $db->query($sql_delete_application);

    echo "Данные успешно удалены.";
    header('Location: admin.php');
}
else {
    echo "userid не найден в базе данных.";
}
    }
    else{
            echo "заполните userid";
        }
    }
        if($_POST['button'] == "Update")
    {
        if(!empty($_POST['update']))
        {
        session_start();
      $userid = $_POST['update'];
    $result = $db->query("SELECT userid FROM users WHERE userid = $userid");
    if ($result->rowCount() > 0) {
    $stmt = $db->prepare("SELECT login FROM users WHERE userid = :userid");
        $stmt->execute([':userid' => $userid]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $_SESSION['login'] = $data['login'];

    $_SESSION['uid'] = $userid;
    header('Location: index.php');
    }
    else {
    echo "userid не найден в базе данных.";
}
    }
        else{
            echo "заполните userid";
        }
    }
}
?>
