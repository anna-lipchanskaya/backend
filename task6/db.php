<?php
include('../db.php');
global $db;
$db = new PDO('mysql:host=localhost;dbname=' . $db_name, $db_login, $db_pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); // Заменить test на имя БД, совпадает с логином uXXXXX
function db_row($stmt) {
  return $stmt->fetch(PDO::FETCH_ASSOC);
}
    function executeQuery($query) {
    global $db;
    try {
        $result = $db->query($query);
        if ($result) {
            // Запрос успешно выполнен
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            // Запрос не удался, логируем ошибку
            logError(db_error());
        }
    } catch (PDOException $e) {
        // Ошибка при выполнении запроса, логируем исключение
        logError($e->getMessage());
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
function executePrepare($query) {
    global $db;
    $stmt = $db->prepare($query);
    return $stmt;
          if ($stmt) {
            // Запрос успешно выполнен
            return $stmt;
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
