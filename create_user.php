<?php

if (isset($_GET['username']) && isset($_GET['password'])){

  include 'lucid-database.php';
  $lucid_data = new LucidData();

  $password = password_hash($_GET['password'], PASSWORD_DEFAULT);
  $username = $_GET['username'];

  $check = password_verify($_GET['password'], $password);

  if($check){
    $result = $lucid_data->add_user($username, $password);
    echo $result;
  }
}

?>
