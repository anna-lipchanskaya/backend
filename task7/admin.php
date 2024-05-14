<?php
/**
 * Задача 6. Реализовать вход администратора с использованием
 * HTTP-авторизации для просмотра и удаления результатов.
 **/
require_once('db.php');   
require_once('auth.php'); 
echo 'Вы успешно авторизовались и видите защищенные паролем данные.'."<br>"; 
?>
    <form action="" method="POST">
            <input name="delete"/>
          <input type="submit" name = "button" value="Delete" />
            <input name="update"/>
          <input type="submit" name = "button" value="Update" />
    </form>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if($_POST['button'] == "Delete")
    {
        if(!empty($_POST['delete']))
    {
    $userid = $_POST['delete'];
    $result = db_get_UserId($userid);

if ($result) {
    // userid существует - выполняем операции удаления
    $result = db_delete_by_id($userid);
    echo "Данные успешно удалены. <br>";
    header(' Location: /admin.php');
    exit();
}
else {
    echo "userid не найден в базе данных. <br>";
}
    }
    else{
            echo "заполните userid <br>";
        }
    }
        if($_POST['button'] == "Update")
    {
        if(!empty($_POST['update']))
        {
        session_start();
      $userid = $_POST['update'];
    $result = db_get_UserId($userid);
    if ($result) {
    $data = db_get_Login($userid);
    $_SESSION['login'] = htmlspecialchars($data['login'], ENT_QUOTES, 'UTF-8');

    $_SESSION['uid'] = $userid;
    header(' Location: /index.php');
        exit();
    }
    else {
    echo "userid не найден в базе данных. <br>";
}
    }
        else{
            echo "заполните userid <br>";
        }
    }
}
$results = db_get_Alluser();

    // Вывод данных
  foreach ($results as $row) {
    echo "Пользователь с login " . htmlspecialchars($row['login'], ENT_QUOTES, 'UTF-8') . " и id " . $row['userid'] . "<br>";
    echo "Name: " . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . "<br>";
    echo "Phone: " . htmlspecialchars($row['phone'], ENT_QUOTES, 'UTF-8') . "<br>";
    echo "Email: " . htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8') . "<br>";
    echo "Data: " . htmlspecialchars($row['data'], ENT_QUOTES, 'UTF-8') . "<br>";
    echo "Gender: " . htmlspecialchars($row['pol'], ENT_QUOTES, 'UTF-8') . "<br>";
    echo "Bio: " . htmlspecialchars($row['bio'], ENT_QUOTES, 'UTF-8') . "<br>";
    echo "Ok: " . htmlspecialchars($row['ok'], ENT_QUOTES, 'UTF-8') . "<br>";
    echo "Languages: " . htmlspecialchars($row['languages'], ENT_QUOTES, 'UTF-8') . "<br><br>";
}
    echo "Статистика языков " . "<br>";
    $query = "SELECT l2.name, count(*) AS count_users
            FROM application3 a 
            INNER JOIN ap_lan3 al3 ON a.userid = al3.userid
            INNER JOIN language2 l2 ON al3.id_language = l2.id
            GROUP BY l2.name";

 $languages = db_get_StatusLanguage();
    // Вывод результатов
    foreach ($languages as $row) {
        echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'). "язык любят:" . htmlspecialchars($row['count_users'], ENT_QUOTES, 'UTF-8'). " пользователя <br>";
    }
?>
