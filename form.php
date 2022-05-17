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

   
	<h1>Форма контракта</h1>

	<form action="index.php" method="POST">

	  <label>
		Имя:<br />
		<input <?php if (errors['name']){print('class="error"');}?> type="text" value=<?php print($values['name']);?> name="name"
		  />
	  </label><br />

	  <label>
		Еmail:<br />
		<input <?php if (errors['email']){print('class="error"');}?> value=<?php print($values['email']);?> name="email"
		  placeholder="test@example.com"
		  type="email" />
	  </label><br />

	  <label>
		Год рождения:<br />
		<select <?php if (errors['year']){print('class="error"');}?> name="year">
	  <option value="Выбрать">Выбрать</option>
	<?php
		for($i=1900;$i<=2022;$i++){
			if ($values['year']==$i){printf("<option selected value=%d>%d год</option>",$i,$i)} else
		  printf("<option value=%d>%d год</option>",$i,$i);
		}
	?>
	</select> <br>
	  </label><br />
	  
	  Пол:<br /> <div <?php if (errors['pol']){print('class="error"');}?>>
	  <label><input <?php if ($values['pol']=='M'){print('checked');}?> type="radio" 
		name="pol" value="M" />
		Мужской</label>
	  <label><input <?php if ($values['pol']=='W'){print('checked');}?> type="radio"
		name="pol" value="W" />
		Женский</label><br />
		</div>
	  Количество конечностей:<br /> <div <?php if (errors['limb']){print('class="error"');}?>>
	  <label><input <?php if ($values['limb']==0){print('checked');}?> type="radio"
		name="limb" value="0" />
		0</label>
	  <label><input <?php if ($values['limb']==1){print('checked');}?> type="radio"
		name="limb" value="1" />
		1</label>
	  <label><input <?php if ($values['limb']==2){print('checked');}?> type="radio"
		name="limb" value="2" />
		2</label>
	  <label><input <?php if ($values['limb']==3){print('checked');}?> type="radio"
		name="limb" value="3" />
		3</label>
	  <label><input <?php if ($values['limb']==4){print('checked');}?> type="radio" 
		name="limb" value="4" />
		4</label><br />
		</div>
	  <label>
		Сверхспособности:
		<br /> 
		<select <?php if (errors['super']){print('class="error"');}?> name="super[]"
		  multiple="multiple">
		  <option <?php if ($values['immortal']){print('selected');}?> value="immortal">Бессмертие</option>
		  <option <?php if ($values['megabrain']){print('selected');}?> value="megabrain" >Мегамозг</option>
		  <option <?php if ($values['teleport']){print('selected');}?> value="teleport">Телепортация</option>
		</select>
	  </label><br />
	  
	  <label>
		Биография:<br />
		<textarea name="bio">$value['bio']</textarea>
	  </label><br />

	  Чекбокс:<br />
	  <label><input <?php if (errors['checkbox']){print('class="error"');}?> type="checkbox" <?php if($values['checkbox']) print('checked');?> name="checkbox"/>
		Я Не болею за Red Bull Racing</label><br />

	  Если уверенны в своем ответе нажимайте:
	  <input type="submit" value="Send" />
	</form>
  </body>
</html>
