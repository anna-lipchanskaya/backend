<p style="font-size: 30px; color: rgb(50, 100, 255)">Форма</p>
        <form action="" method="POST">
        <label>
          ФИО:<br />
          <input name="name"
          placeholder="Введите ваше ФИО" />
        </label><br />
        <label>
        Телефон:<br />
        <input required name="phone"
          type="tel"
          placeholder="Введите ваш телефон" />
      </label><br />
      <label>
        Email:<br />
        <input required name="email"
          type="email"
          placeholder="Введите вашу почту" /></label>
          <label>
            <br />
            Дата рождения:<br />
            <input name="data"
              value="2000-01-01"
              type="date" />
          </label><br />
          Пол:<br />
      <label><input type="radio" checked="checked"
        name="pol" value="M" />
        Мужской</label>
      <label><input type="radio"
        name="pol" value="W" />
        Женский</label><br />
                 <label>
          Любимый язык программирования:
          <br />
        <select name="abilities[]" multiple="multiple">
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
          <textarea name="bio" placeholder="Ваша биография" ></textarea>
        </label><br />
      <label><input required type="checkbox" checked="checked"
        name="ok" />
        С контрактом ознакомлен(а)</label><br />
        <br />
        <input type="submit" value="Сохранить" />
      </form>
