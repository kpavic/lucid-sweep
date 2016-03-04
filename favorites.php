<?php
session_start();
if(isset($_SESSION['user_id'])){
  $log_in_out = 'logout=yes';
  $username = $_SESSION['username'];
  $login="Logout";

  include 'lucid-database.php';
  include 'lucid-tube.php';
  $lucid_data = new LucidData();
  $lucid_tube = new LucidTube();
  $vid_list = $lucid_data->fetch_favorites($_SESSION['user_id']);

  $favorite_data = $lucid_tube->get_vid_list($vid_list);

} else {
  $log_in_out = 'login=yes';
  $username='None';
  $login="Login";
  $favorite_data = '';
}

?>

<!doctype html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Fake Youtube</title>
    <script>
      function removeFavorite(str) {
        if (str.length==0) { 
          return;
        }
        if (window.XMLHttpRequest) {
          xmlhttp=new XMLHttpRequest();
        } else {
          xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function() {
          if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            document.getElementById(str).innerHTML=xmlhttp.responseText;
          }
        }
        xmlhttp.open("GET","lucid-database.php?unfavorite="+str,true);
        xmlhttp.send();
        window.open('favorites.php' ,'_self')
      }
    </script>
  </head>
  <body>
    <div id="navigation">
      <a style="float: left;" href="index.php">Fake Youtube</a>
      <a href="favorites.php">My videos</a>
      <a href="login.php?<?= $log_in_out ?>"><?= $login ?></a>
    </div>
    <div id="main_search">
    </div>
    <div id="results" onload=getResult(yes)> <?= $favorite_data ?> </div>
  </body>
</html>
