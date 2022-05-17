<?php
header('Content-Type: text/html; charset=UTF-8');
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if (!empty($_SESSION['login'])) {
  header('Location: index.php');
  }else{
?>
<style>
  .form-sign-in{
    max-width: 960px;
    text-align: center;
    margin: 0 auto;
  }
</style>
<div class="form-sign-in">
<form action="login.php" method="post">
  <input name="login" /> Логин<br>
  <input name="pass" type="password"/> Пароль<br>
  <input type="submit" value="Войти" />
</form>
</div>
<?php
  }
}
else {
  $l=$_POST['login'];
  $p=$_POST['pass'];
  $uid=0;
  $error=TRUE;
include ('connect.php');
  if(!empty($l) and !empty($p)){
    try{
      $chk=$db->prepare("select * from username where login=?");
      $chk->bindParam(1,$l);
      $chk->execute();
      $username=$chk->fetchALL();
      if(password_verify($p,$username[0]['pass'])){
        $uid=$username[0]['id'];
        $error=FALSE;
      }
    }
    catch(PDOException $e){
      print('Error : ' . $e->getMessage());
      exit();
    }
  }
  if($error==TRUE){
  include ('error.php');
    session_destroy();
    exit();
  }
  $_SESSION['login'] = $l;
  $_SESSION['uid'] = $uid;
  header('Location: index.php');
}
