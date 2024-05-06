<?php
include('../db.php');
global $db;
$db = new PDO('mysql:host=localhost;dbname=' . $db_name, $db_login, $db_pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); // Заменить test на имя БД, совпадает с логином uXXXXX
function db_row($stmt) {
  return $stmt->fetch(PDO::FETCH_ASSOC);
}
function db_row_All($stmt) {
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    function executeQuery($query, $default = FALSE) {
    global $db;
        $result = $db->query($query);
        if ($result) {
            // Запрос успешно выполнен
            return db_row_All($result);
        } else {
            return $defaul;
}
    }

function db_query($query) {
  global $db;
  $stmt = $db->prepare($query);
  $args = func_get_args();
  array_shift($args);
  $res = $stmt->execute($args);
  if ($res) {
    return db_row_All($stmt);
  } else {
    return false;
  }
}
function db_result($query) {
  global $db;
  $stmt = $db->prepare($query);
  $args = func_get_args();
  array_shift($args);
  $res = $stmt->execute($args);
  if ($res) {
    if ($row = db_row($stmt)) {
      return $row;
    }
    }
  else {
    return false;
  }
}

function db_command($query) {
  global $db;
  $q = $db->prepare($query);
  $args = func_get_args();
  array_shift($args);
  $res = $q->execute($args);
  return $res;
}
function db_insert_id() {
  global $db;
  return $db->lastInsertId();
}

function db_get_Alluser($default = FALSE) {
  $query = "SELECT a.userid, a.name, a.phone, a.email, a.data, a.pol, a.bio, a.ok, u.login, GROUP_CONCAT(DISTINCT l2.name SEPARATOR ', ') as languages
                        FROM application3 a
                        INNER JOIN users u ON a.userid = u.userid
                        LEFT JOIN ap_lan3 al3 ON a.userid = al3.userid
                        LEFT JOIN language2 l2 ON al3.id_language = l2.id
                        GROUP BY a.userid, a.name, a.phone, a.email, a.data, a.pol, a.bio, a.ok, u.login";
  $value = db_query($query);
  if (!$value) {
    return $default;
  }
  else {
    return $value;
  }
}

function db_get_StatusLanguage($default = FALSE) {
$query = "SELECT l2.name, count(*) AS count_users
            FROM application3 a 
            INNER JOIN ap_lan3 al3 ON a.userid = al3.userid
            INNER JOIN language2 l2 ON al3.id_language = l2.id
            GROUP BY l2.name";
  $value = db_query($query);
  if (!$value) {
    return $default;
  }
  else {
    return $value;
  }
}

function db_get_UserId($userid) {
  $value = db_query("SELECT userid FROM users WHERE userid = ?", $userid);
  if ($value == FALSE) {
    return FALSE;
  }
  else {
    return TRUE;
}
}

function db_delete_by_id($userid) {
  $value1 = db_query("DELETE FROM users WHERE userid = ?", $userid);
  $value2 = db_query("DELETE FROM ap_lan3 WHERE userid = ?", $userid);
  $value3 = db_query("DELETE FROM application3 WHERE userid = ?", $userid);
}

function db_get_Login($userid, $default = FALSE) {
  $value = db_result("SELECT login FROM users WHERE userid = ?", $userid);
  if (!$value) {
    return $default;
  }
  else {
    return $value;
  }
}
function db_get_Pass_Login($default = FALSE) {
  $value = db_result("SELECT login, password FROM admin");
    if (!$value) {
          return $default;
  }
  else {
    return $value;
  }
}

function db_get_Pass_Login_user($login, $default = FALSE) {
  $value = db_result("SELECT * FROM users WHERE login = ?", $login);
    if (!$value) {
          return $default;
  }
  else {
    return $value;
  }
}

function db_get_Languages($userid) {
  $value = db_query("SELECT l.name
FROM ap_lan3 AS a JOIN language2 AS l ON a.id_language = l.id WHERE a.userid = ? ", $userid);
  if ($value == FALSE) {
    return FALSE;
  }
  else {
    return $value;
}
}

function db_get_form_user($userid, $default = FALSE) {
  $value = db_result("SELECT name, phone, email, data, pol, bio, ok  FROM application3 WHERE userid = ?", $userid);
    if (!$value) {
          return $default;
  }
  else {
    return $value;
  }
}

function db_get_language_id($name, $default = FALSE) {
  $value = db_result("SELECT id FROM language2 WHERE name = ?", $name);
    if (!$value) {
          return $default;
  }
  else {
    return $value;
  }
}

function db_set_application($userid, $login, $hashedPassword, $name, $phone, $email, $data, $pol, $bio, $ok, $abilities) {
 $errors = FALSE;
  if (empty($name)) {
    // Выдаем куку на день с флажком об ошибке в поле name.
    setcookie('name_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }elseif (!preg_match('/^[\p{L}\s]+$/u', $name)) {
    setcookie('name_error_len', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
} elseif (strlen($name) > 150) {
    setcookie('name_error_struct', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
}
  if (empty($phone)) {
    setcookie('phone_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
} elseif (!preg_match('/^\d+$/', $phone)) {
    setcookie('phone_error_struct', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
}elseif (strlen($phone) > 11) {
    setcookie('phone_error_len', '1', time() + 24 * 60 * 60);
            $errors = TRUE;
        }
  if (empty($email)) {
    setcookie('email_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    setcookie('email_error_struct', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
}elseif (strlen($email) > 150) {
    setcookie('email_error_len', '1', time() + 24 * 60 * 60);
            $errors = TRUE;
        }
if (empty($pol)) {
    setcookie('pol_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
} elseif ($pol !== 'W' && $pol !== 'M') {
    setcookie('pol_error_struct', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
}
if ($ok !== 'on') {
    setcookie('ok_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
}
  if (empty($data)) {
    setcookie('data_error', '1', time() + 24 * 60 * 60);
  $errors = TRUE;
} elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data)) {
    setcookie('data_error_struct', '1', time() + 24 * 60 * 60);
  $errors = TRUE;
}
$allowed_languages = array("Pascal", "C", "C++", "JavaScript", "PHP", "Python", "Java", "Haskel");

if (empty($abilities)) {
    setcookie('abilities_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
} else {
    foreach ($abilities as $language) {
        if (!in_array($language, $allowed_languages)) {
    setcookie('abilities_error_struct', '1', time() + 24 * 60 * 60);
            $errors = TRUE;
            break;
        }
    }
}
if (empty($bio)) {
    setcookie('bio_error', '1', time() + 24 * 60 * 60);
  $errors = TRUE;
}elseif (strlen($bio) > 300) {
    setcookie('bio_error_len', '1', time() + 24 * 60 * 60);
            $errors = TRUE;
        }
if($errors == FALSE) 
{
  return "Error";
}
else{
  $v = db_get_UserId($userid);
  if ($v == FALSE) {
      $q1 = db_command("INSERT INTO application3 (name, phone, email, data, pol, bio, ok) VALUES (?, ?, ?, ?, ?, ?, ?)", $name, $phone, $email, $data, $pol, $bio, $ok);
    if($q1 <= 0) 
    {
      return FALSE;
        }
      $UserId = db_insert_id();

      $q2 = db_command("INSERT INTO users (userid, login, password) VALUES (?, ?, ?)", $UserId, $login, $hashedPassword);
        if($q2 <= 0) 
    {
      return FALSE
        }

      foreach ($abilities as $ability) {
    $languageId = db_get_language_id($ability);
    $q3 = db_command("INSERT INTO ap_lan3 (userid, id_language) VALUES (?, ?)", $UserId, $languageId);
            if($q3 <= 0) 
    {
      return FALSE;
        }
      }
    return TRUE;
  }
  /*else {
    $q = "UPDATE variable SET value = ? WHERE name = ?";
    return (db_command($q1, $name, $name) > 0);
  }*/
}
}


    function Query($query) {
    global $db;
    try {
        $result = $db->query($query);
        if ($result) {
            // Запрос успешно выполнен
            return $result;
        } else {
            // Запрос не удался, логируем ошибку
            logError(db_error());
        }
    } catch (PDOException $e) {
        // Ошибка при выполнении запроса, логируем исключение
        logError($e->getMessage());
    }
}

function db_error() {
    global $db;
    return $db->errorInfo();
}

function logError($errorInfo) {
    error_log(print_r($errorInfo, true));
}
?>
