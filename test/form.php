<html>
  <head>
    <style>
/* Сообщения об ошибках и поля с ошибками выводим с красным бордюром. */
.error {
  border: 2px solid red;
}
    </style>
  </head>
  <body>

<?php
if (!empty($messages)) {
  print('<div id="messages">');
  // Выводим все сообщения.
  foreach ($messages as $message) {
    print($message);
  }
  print('</div>');
}

// Далее выводим форму отмечая элементы с ошибками классом error
// и задавая начальные значения элементов ранее сохраненными.
?>

    <form action="" method="POST">
      <input name="fio" <?php if ($errors['fio']) {print 'class="error"';} ?> value="<?php print $values['fio']; ?>" />
      <input type="submit" name = "button" value="ok" />
      <input type="submit" name="button" id = "myButton" value="exit" />
      <script>
        document.addEventListener("DOMContentLoaded", function() {
    var button = document.getElementById("myButton");
    button.style.display = "none";
    if (session_start() && (!empty($_SESSION['login']))) {
        button.style.display = "block";
    }
});
      </script>
    </form>
  </body>
</html>
