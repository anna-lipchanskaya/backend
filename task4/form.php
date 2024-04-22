<html>
<head>
<title>Задание №4</title>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
<style>
  /* Сообщения об ошибках и поля с ошибками выводим с красным бордюром. */
.error {
  border: 2px solid red !important;
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
<div class="main-block">
<div class="left-part">
<i class="fas fa-envelope"></i>
<i class="fas fa-at"></i>
<i class="fas fa-mail-bulk"></i>
</div>
<form action="" method="POST">
<h1 style="display: flex;
justify-content: center;
align-items: center;">Заявка</h1>
    <label>
      ФИО:<br />
      <input 
      name="name" style = "width: calc(100% - 18px);
padding: 8px;
margin-bottom: 20px;
border: 1px solid #1c87c9;
outline: none;"
      placeholder="Введите ваше ФИО" <?php if ($errors['name'] || $errors['name_struct'] || $errors['name_len'] ) {print 'class="error"';} ?> value="<?php print $values['name']; ?>"/>
    <label>
    Телефон:<br />
    <input  style = "width: calc(100% - 18px);
padding: 8px;
margin-bottom: 20px;
border: 1px solid #1c87c9;
outline: none;" name="phone"
      type="tel"
      placeholder="Введите ваш телефон" <?php if ($errors['phone'] || $errors['phone_struct'] || $errors['phone_len'] ) {print 'class="error"';} ?> value="<?php print $values['phone']; ?>"/>
  </label><br />
  <label>
    Email:<br />
    <input  style = "width: calc(100% - 18px);
padding: 8px;
margin-bottom: 20px;
border: 1px solid #1c87c9;
outline: none;" name="email"
      placeholder="Введите вашу почту" <?php if ($errors['email'] || $errors['email_struct'] || $errors['email_len'] ) {print 'class="error"';} ?> value="<?php print $values['email']; ?>"/></label>
      <label>
        <br />
        Дата рождения:<br />
        <input style = "width: calc(100% - 18px);
padding: 8px;
margin-bottom: 20px;
border: 1px solid #1c87c9;
outline: none;" name="data"
          type="date" value="<?php echo isset($values['data']) ? $values['data'] : '2000-01-01'; ?>"
    type="date" <?php if ($errors['data']) {echo 'class="error"';} ?>/>
      </label>
Пол:<br />
<label><input type="radio" 
    name="pol" value="M" <?php if ($values['pol'] === 'M') {echo 'checked';} ?> <?php if ($errors['pol'] || $errors['pol_struct']) {echo 'class="error"';} ?>/>
    Мужской</label>
<label><input type="radio"
    name="pol" value="W" <?php if ($values['pol'] === 'W') {echo 'checked';} ?> <?php if ($errors['pol'] || $errors['pol_struct']) {echo 'class="error"';} ?>/>
    Женский</label><br />
                <br />
      Любимый язык программирования:
      <br />
<?php $abilities_array = is_array($abilities_array) ? $abilities_array : [];?>
    </label><br />
  <select style="width: calc(100% - 18px); padding: 8px; margin-bottom: 20px; border: 1px solid #1c87c9; outline: none;" name="abilities[]" multiple="multiple" <?php if ($errors['abilities'] || $errors['abilities_struct']) {echo 'class="error"';} ?>>
    <option disabled>Выберите любимый язык пр.</option>
    <option value="Pascal" <?php if(in_array('Pascal', unserialize($values['abilities']))) {echo 'selected';} ?>>Pascal</option>
    <option value="C" <?php if(in_array('C', unserialize($values['abilities']))) {echo 'selected';} ?>>C</option>
    <option value="C++" <?php if(in_array('C++', unserialize($values['abilities']))) {echo 'selected';} ?>>C++</option>
    <option value="JavaScript" <?php if(in_array('JavaScript', unserialize($values['abilities']))) {echo 'selected';} ?>>JavaScript</option>
    <option value="PHP" <?php if(in_array('PHP', unserialize($values['abilities']))) {echo 'selected';} ?>>PHP</option>
    <option value="Python" <?php if(in_array('Python', unserialize($values['abilities']))) {echo 'selected';} ?>>Python</option>
    <option value="Java" <?php if(in_array('Java', unserialize($values['abilities']))) {echo 'selected';} ?>>Java</option>
    <option value="Haskel"<?php if (in_array('Haskel', ($values['abilities']))) { echo ' selected'; } ?>>Haskel</option>
</select>
    <label>
      Биография:<br />
     <textarea name="bio" placeholder="<?php print $values['bio']; ?>" <?php if ($errors['bio'] || $errors['bio_len']) { print 'class="error"'; } ?>><?php print $values['bio']; ?></textarea>
<label><input type="checkbox"
    name="ok" <?php if ($values['ok'] === 'on') {echo 'checked';} ?> <?php if ($errors['ok']) {echo 'class="error"';} ?>/>    С контрактом ознакомлен(а)</label>
    <br />
<button type="submit" href="/">Сохранить</button>
</form>
</div>
</body>
</html>
