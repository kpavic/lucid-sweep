<?php
include 'lucid-database.php';

if(isset($_SESSION['id']) && $_GET['logout']=='yes'){
  unset($_SESSION['id']);

} elseif(!isset($_SESSION['id'])&&($_GET['login']=='yes')){
  // Prepare login data
  
} elseif(isset($_POST['username']) && isset($_POST['password']))
  // Login user if username and password matches
  $username = $_POST['username'];
  $password = $_POST['password'];

  $lucid_data = new LucidData();
  $login = $lucid_data->user_login($username, $password);
  
  if($login==TRUE){
    session_start();
    $_SESSION['username']= $username;
    header("location: index.php");
  } else {
    $error = "Unijeli ste neispravne podatke!";
  }

?>


<!DOCTYPE html>
<html>
<head>
  <title>Fake Youtube - Prijava</title>
  <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
  <div id="navigation">
    <a style="float: left;" href="index.php">Fake Youtube</a>
    <a href="favorites.php">My videos</a>
    <a href="logout.php">Logout</a>
  </div>

  <div id="main_search">
    <h2>Unesite svoje login podatke</h2>
    <form action="login.php" method="post">
      <label>Korisnicko ime :</label>
      <input id="name" name="username" type="text">
      <label>Lozinka :</label>
      <input id="password" name="password" type="password">
      <input name="submit" type="submit" value=" Login ">
      <span style="color: red;"><?= $error; ?></span>
</form>
</div>
</body>
</html>
