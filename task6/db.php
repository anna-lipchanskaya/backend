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
  return $res = $q->execute($args);
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
    return $default;
  }
  else {
    return $value;
  }
}

function db_set($name, $value) {
  if (strlen($name) == 0) {
    return;
  }

  $v = db_get($name);
  if ($v === FALSE) {
    $q = "INSERT INTO variable VALUES (?, ?)";
    return db_command($q, $name, $value) > 0;
  }
  else {
    $q = "UPDATE variable SET value = ? WHERE name = ?";
    return db_command($q, $value, $name) > 0;
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
