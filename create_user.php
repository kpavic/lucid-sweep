<?php

$password = password_hash($_GET['password'], PASSWORD_DEFAULT);

echo $password;

$check = password_verify($_GET['password'], $password);

if($check){
  echo "OK";
}

?>
