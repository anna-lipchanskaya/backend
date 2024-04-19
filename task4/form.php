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
html, body {
min-height: 100%;
padding: 0;
margin: 0;
font-family: Roboto, Arial, sans-serif;
font-size: 14px;
color: #666;
}
h1 {
margin: 0 0 20px;
font-weight: 400;
color: #1c87c9;
}
p {
margin: 0 0 5px;
}
.main-block {
display: flex;
flex-direction: column;
justify-content: center;
align-items: center;
min-height: 100vh;
background: #1c87c9;
}
form {
padding: 25px;
margin: 25px;
box-shadow: 0 2px 5px #f5f5f5; 
background: #f5f5f5; 
}
.fas {
margin: 25px 10px 0;
font-size: 72px;
color: #fff;
}
.fa-envelope {
transform: rotate(-20deg);
}
.fa-at , .fa-mail-bulk{
transform: rotate(10deg);
}
.f {
width: calc(100% - 18px);
padding: 8px;
margin-bottom: 20px;
border: 1px solid #1c87c9;
outline: none;
}
input::placeholder {
color: #666;
}
button {
width: 100%;
padding: 10px;
border: none;
background: #1c87c9; 
font-size: 16px;
font-weight: 400;
color: #fff;
}
button:hover {
background: #2371a0;
} 
@media (min-width: 1300px) {
.main-block {
flex-direction: row;
}
.left-part, form {
width: 50%;
}
.fa-envelope {
margin-top: 0;
margin-left: 20%;
}
.fa-at {
margin-top: -10%;
margin-left: 65%;
}
.fa-mail-bulk {
margin-top: 2%;
margin-left: 28%;
}) {
.main-block {
flex-direction: row;
}
.left-part, form {
width: 50%;
}
.fa-envelope {
margin-top: 0;
margin-left: 20%;
}
.fa-at {
margin-top: -10%;
margin-left: 65%;
}
.fa-mail-bulk {
margin-top: 2%;
margin-left: 28%;
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
    <select style = "width: calc(100% - 18px);
padding: 8px;
margin-bottom: 20px;
border: 1px solid #1c87c9;
outline: none;" name="abilities[]" multiple="multiple" <?php if ($errors['abilities'] || $errors['abilities_struct']) {echo 'class="error"';} ?> value="<?php echo unserialize($values['abilities']); ?>">
            <option disabled>Выберите любимый язык пр.</option>
            <option value="Pascal">Pascal</option>
            <option value="C">C</option>
            <option value="C++">C++</option>
            <option value="JavaScript">JavaScript</option>
            <option value="PHP">PHP</option>
            <option value="Python">Python</option>
            <option value="Java">Java</option>
            <option value="Haskel">Haskel</option>
        </select>
    </label><br />
    <label>
      Биография:<br />
     <textarea style="width: calc(100% - 18px); padding: 8px; margin-bottom: 20px; border: 1px solid #1c87c9; outline: none;" name="bio" placeholder="<?php print $values['bio']; ?>" <?php if ($errors['bio'] || $errors['bio_len']) { print 'class="error"'; } ?>><?php print $values['bio']; ?></textarea>
<label><input type="checkbox"
    name="ok" <?php if ($values['ok'] === 'on') {echo 'checked';} ?> <?php if ($errors['ok']) {echo 'class="error"';} ?>/>    С контрактом ознакомлен(а)</label>
    <br />
<button type="submit" href="/">Сохранить</button>
</form>
</div>
</body>
</html>
