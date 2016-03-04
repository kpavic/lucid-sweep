<?php
session_start();

if (isset($_GET['username']) && isset($_GET['password'])){


  include 'lucid-database.php';
  $lucid_data = new LucidData();

  $username = $_GET['username'];
  $password = $_GET['password'];

  $check = $lucid_data->user_login($username, $password);

  if($check == TRUE){
    $user_id = $lucid_data->get_user_id($username);
    $result = $lucid_data->remove_user($user_id);
    echo $result;
  }
}

?>
