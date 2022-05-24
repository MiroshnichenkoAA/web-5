<?php
// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');
session_start();
// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  $messages = array();
 if (!empty($_COOKIE['save'])) {
    setcookie('save', '', 100000);
    setcookie('login', '', 100000);
    setcookie('pass_in', '', 100000);
    $messages[] = 'Спасибо, результаты сохранены.';
    if (!empty($_COOKIE['pass_in'])) {
      $messages[] = sprintf('Вы можете <a href="login.php">войти</a> с логином <strong>%s</strong>
        и паролем <strong>%s</strong> для изменения данных.',
        strip_tags($_COOKIE['login']),
        strip_tags($_COOKIE['pass_in']));
    }
    setcookie('fio_value', '', 100000);
    setcookie('mail_value', '', 100000);
    setcookie('year_value', '', 100000);
    setcookie('sex_value', '', 100000);
    setcookie('limb_value', '', 100000);
    setcookie('bio_value', '', 100000);
    setcookie('immortal_value', '', 100000);
    setcookie('megabrain_value', '', 100000);
    setcookie('teleport_value', '', 100000);
    setcookie('privacy_value', '', 100000);
  }
  $errors = array();
  $error=FALSE; 
  $errors['name'] = !empty($_COOKIE['fio_error']);
  $errors['email'] = !empty($_COOKIE['mail_error']);
  $errors['year'] = !empty($_COOKIE['year_error']);
  $errors['pol'] = !empty($_COOKIE['sex_error']);
  $errors['limb'] = !empty($_COOKIE['limb_error']);
  $errors['super'] = !empty($_COOKIE['powers_error']);
  $errors['checkbox'] = !empty($_COOKIE['privacy_error']);

  if ($errors['name']) {
      setcookie('fio_error', '', 100000);
    $messages[] = '<div class="error">Заполните имя.</div>';
     $error=TRUE;
  }
  if ($errors['email']) {
        setcookie('mail_error', '', 100000);
    $messages[] = '<div class="error">Заполните или исправьте почту.</div>';
     $error=TRUE;
  }
  if ($errors['year']) {
         setcookie('year_error', '', 100000);
    $messages[] = '<div class="error">Выберите год рождения.</div>';
     $error=TRUE;
  }
  if ($errors['pol']) {
         setcookie('sex_error', '', 100000);
    $messages[] = '<div class="error">Выберите пол.</div>';
     $error=TRUE;
  }
  if ($errors['limb']) {
        setcookie('limb_error', '', 100000);
    $messages[] = '<div class="error">Выберите сколько у вас конечностей.</div>';
     $error=TRUE;
  }
  if ($errors['super']) {
          setcookie('powers_error', '', 100000);
    $messages[] = '<div class="error">Выберите хотя бы одну суперспособность.</div>';
     $error=TRUE;
  }
  if ($errors['checkbox']) {
          setcookie('privacy_error', '', 100000);
    $messages[] = '<div class="error">Необходимо согласиться с политикой конфиденциальности.</div>';
     $error=TRUE;
  }

  $values = array();
  $values['name'] = empty($_COOKIE['fio_value']) ? '' : $_COOKIE['fio_value'];
  $values['email'] = empty($_COOKIE['mail_value']) ? '' : $_COOKIE['mail_value'];
  $values['year'] = empty($_COOKIE['year_value']) ? 0 : $_COOKIE['year_value'];
  $values['pol'] = empty($_COOKIE['sex_value']) ? '' : $_COOKIE['sex_value'];
  $values['limb'] = empty($_COOKIE['limb_value']) ? '' : $_COOKIE['limb_value'];
  $values['immortal'] = empty($_COOKIE['immortal_value']) ? 0 : $_COOKIE['immortal_value'];
  $values['megabrain'] = empty($_COOKIE['megabrain_value']) ? 0 : $_COOKIE['megabrain_value'];
  $values['teleport'] = empty($_COOKIE['teleport_value']) ? 0 : $_COOKIE['teleport_value'];
  $values['bio'] = empty($_COOKIE['bio_value']) ? '' : $_COOKIE['bio_value'];
  $values['checkbox'] = empty($_COOKIE['privacy_value']) ? FALSE : $_COOKIE['privacy_value'];
  if (!$error and !empty($_COOKIE[session_name()]) and !empty($_SESSION['login'])) {
    require('connect.php');
    try{
      $get=$db->prepare("select * from form where id=?");
      $get->bindParam(1,$_SESSION['uid']);
      $get->execute();
      $inf=$get->fetchALL();
      $values['name']=$inf[0]['name'];
      $values['email']=$inf[0]['email'];
      $values['year']=$inf[0]['year'];
      $values['pol']=$inf[0]['sex'];
      $values['limb']=$inf[0]['limbs'];
      $values['bio']=$inf[0]['bio'];

      $get2=$db->prepare("select p_name from power where p_id=?");
      $get2->bindParam(1,$_SESSION['uid']);
      $get2->execute();
      $inf2=$get2->fetchALL();
      for($i=0;$i<count($inf2);$i++){
        if($inf2[$i]['p_name']=='immortal'){
          $values['immortal']=1;
        }
        if($inf2[$i]['p_name']=='megabrain'){
          $values['megabrain']=1;
        }
        if($inf2[$i]['p_name']=='teleport'){
          $values['teleport']=1;
        }
      }
    }
    catch(PDOException $e){
      print('Error: '.$e->getMessage());
      exit();
    }
    printf('Вход с логином %s, uid %d', $_SESSION['login'], $_SESSION['uid']);
  }
  include('form.php');
}
else{
// Проверяем ошибки.
$errors = FALSE;
//проверка имени
if (empty($_POST['name'])) {
  setcookie('fio_error', '1', time() + 24 * 60 * 60);
  setcookie('fio_value', '', 100000);
  $errors = TRUE;
}
else {
  setcookie('fio_value', $_POST['name'], time() + 12*30 * 24 * 60 * 60);
  setcookie('fio_error','',100000);
}
//проверка почты
if (empty($_POST['email']) or !filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)) {
  setcookie('mail_error', '1', time() + 24 * 60 * 60);
  setcookie('mail_value', '', 100000);
  $errors = TRUE;
}
else {
  setcookie('mail_value', $_POST['email'], time() + 12*30 * 24 * 60 * 60);
  setcookie('mail_error','',100000);
}
//проверка года
if ($_POST['year']=='Выбрать') {
  setcookie('year_error', '1', time() + 24 * 60 * 60);
  setcookie('year_value', '', 100000);
  $errors = TRUE;
}
else {
  setcookie('year_value', intval($_POST['year']), time() + 12*30 * 24 * 60 * 60);
  setcookie('year_error','',100000);
}
//проверка пола
if (!isset($_POST['pol'])) {
  setcookie('sex_error', '1', time() + 24 * 60 * 60);
  setcookie('sex_value', '', 100000);
  $errors = TRUE;
}
else {
  setcookie('sex_value', $_POST['pol'], time() + 12*30 * 24 * 60 * 60);
  setcookie('sex_error','',100000);
}
//проверка конечностей
if (!isset($_POST['limb'])) {
  setcookie('limb_error', '1', time() + 24 * 60 * 60);
  setcookie('limb_value', '', 100000);
  $errors = TRUE;
}
else {
  setcookie('limb_value', $_POST['limb'], time() + 12*30 * 24 * 60 * 60);
  setcookie('limb_error','',100000);
}
//проверка суперспособностей
if (!isset($_POST['super'])) {
  setcookie('powers_error', '1', time() + 24 * 60 * 60);
  setcookie('immortal_value', '', 100000);
  setcookie('megabrain_value', '', 100000);
  setcookie('teleportation_value', '', 100000);
  $errors = TRUE;
}
else {
  $pwrs=$_POST['super'];
  $a=array(
    "immortal_value"=>0,
    "megabrain_value"=>0,
    "teleportation_value"=>0
  );
  foreach($pwrs as $pwr){
    if($pwr=='immortal'){setcookie('immortal_value', 1, time() + 12*30 * 24 * 60 * 60); $a['immortal_value']=1;} 
    if($pwr=='megabrain'){setcookie('megabrain_value', 1, time() + 12*30 * 24 * 60 * 60);$a['megabrain_value']=1;} 
    if($pwr=='teleport'){setcookie('teleport_value', 1, time() + 12*30 * 24 * 60 * 60);$a['teleport_value']=1;} 
  }
  foreach($a as $c=>$val){
    if($val==0){
      setcookie($c,'',100000);
    }
  }
}
//запись куки для биографии
setcookie('bio_value',$_POST['bio'],time()+ 12*30*24*60*60);
//проверка согласия с политикой конфиденциальности
if(empty($_SESSION['login'])){
    if(!isset($_POST['checkbox'])){
      setcookie('privacy_error','1',time()+ 24*60*60);
      setcookie('privacy_value', '', 100000);
      $errors=TRUE;
    }
    else{
      setcookie('privacy_value',TRUE,time()+ 60*60);
      setcookie('privacy_error','',100000);
    }
  }

if ($errors) {
  header('Location: index.php');
  exit();
}
else {
  setcookie('fio_error', '', 100000);
  setcookie('mail_error', '', 100000);
  setcookie('year_error', '', 100000);
  setcookie('sex_error', '', 100000);
  setcookie('limb_error', '', 100000);
  setcookie('powers_error', '', 100000);
  setcookie('bio_error', '', 100000);
  setcookie('privacy_error', '', 100000);
}

 $name=htmlspecialchars ($_POST['name'], ENT_QUOTES, 'UTF-8');
  $email=htmlspecialchars ($_POST['email'], ENT_QUOTES, 'UTF-8');
  $year=htmlspecialchars ($_POST['year'], ENT_QUOTES, 'UTF-8');
  $pol=htmlspecialchars ($_POST['pol'], ENT_QUOTES, 'UTF-8');
  $limb=htmlspecialchars ($_POST['limb'], ENT_QUOTES, 'UTF-8');
  $super=htmlspecialchars ($_POST['super'], ENT_QUOTES, 'UTF-8');
  $bio=htmlspecialchars ($_POST['bio'];
  if(empty($_SESSION['login'])){
    $check=$_POST['checkbox'];
  }
// Сохранение в базу данных.

include ('connect.php');
if (!empty($_COOKIE[session_name()]) && !empty($_SESSION['login']) and !$errors) {
    $id=$_SESSION['uid'];
    $upd=$db->prepare("update form set name=:name,email=:email,year=:year,sex=:sex,limbs=:limbs,bio=:bio where id=:id");
    $cols=array(
      ':name'=>$name,
      ':mail'=>$email,
      ':date'=>$year,
      ':sex'=>$sex,
      ':limbs'=>$limb,
      ':bio'=>$bio
    );
    foreach($cols as $k=>&$v){
      $upd->bindParam($k,$v);
    }
    $upd->bindParam(':id',$id);
    $upd->execute();
    $del=$db->prepare("delete from power where p_id=?");
    $del->execute(array($id));
    $upd1=$db->prepare("insert into power set p_name=:power,p_id=:id");
    $upd1->bindParam(':id',$id);
    foreach($super as $pwr){
      $upd1->bindParam(':power',$pwr);
      $upd1->execute();
    }
  } else {
    if(!$errors){
      $login = substr(uniqid(),-8);
      $pass_in = substr(md5(substr(uniqid(),-4)),0,16);
      $pass_hash=password_hash($pass_in,PASSWORD_DEFAULT);
      setcookie('login', $login);
      setcookie('pass_in', $pass_in);
  

try {
  $stmt = $db->prepare("INSERT INTO form SET name=:name,email=:mail,year=:date,sex=:sex,limbs=:limb,bio=:bio");
  $stmt->bindParam(':name',$_POST['name']);
  $stmt->bindParam(':mail',$_POST['email']);
  $stmt->bindParam(':date',$_POST['year']);
  $stmt->bindParam(':sex',$_POST['pol']);
  $stmt->bindParam(':limb',$_POST['limb']);
  $stmt->bindParam(':bio',$_POST['bio']);
  $stmt -> execute();
  $id=$db->lastInsertId();
  $pwr=$db->prepare("INSERT INTO power SET p_name=:power,p_id=:id");
  $pwr->bindParam(':id',$id);
  foreach($_POST['super'] as $power){
    $pwr->bindParam(':power',$power); 
    $pwr->execute();  
  }
    $usr=$db->prepare("insert into username set id=?,login=?,pass=?");

        $usr->execute(array($id,$login,$pass_hash));

}
catch(PDOException $e){
  print('Error : ' . $e->getMessage());
  exit();
}
    }
  }
// Сохраняем куку с признаком успешного сохранения.
setcookie('save', '1');

// Делаем перенаправление.
header('Location: index.php');
}
