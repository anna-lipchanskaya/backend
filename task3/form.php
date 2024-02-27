<p style="font-size: 30px; color: rgb(50, 100, 255)">Форма</p>
        <form>
        <label>
          ФИО:<br />
          <input required name="field-name-1"
          placeholder="Введите ваше ФИО" />
        </label><br />
        <label>
        Телефон:<br />
        <input required name="field-phone"
          type="tel"
          placeholder="Введите ваш телефон" />
      </label><br />
      <label>
        Email:<br />
        <input required name="field-email"
          type="email"
          placeholder="Введите вашу почту" /></label>
          <label>
            <br />
            Дата рождения:<br />
            <input name="field-date"
              value="2023-09-14"
              type="date" />
          </label><br />
          Пол:<br />
      <label><input type="radio" checked="checked"
        name="radio-group-1" value="Значение1" />
        Мужской</label>
      <label><input type="radio"
        name="radio-group-1" value="Значение2" />
        Женский</label><br />
        <label>
          Биография:<br />
          <textarea name="field-name-2" placeholder="Ваша биография" ></textarea>
        </label><br />
      <label><input required type="checkbox" checked="checked"
        name="check-1" />
        С контрактом ознакомлен(а)</label><br />
        <br />
        <input type="submit" value="Сохранить" />
      </form>
