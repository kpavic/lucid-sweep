<?php
session_start();
if (isset($_SESSION['user_id'])){
  $log_in_out = 'logout=yes';
  $username = $_SESSION['username'];
  $login='Logout';
} else {
  $log_in_out = 'login=yes';
  $username='None';
  $login='Login';
}

?>

<!doctype html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Fake Youtube</title>
    <script>
      function getResult(str) {
        if (str.length==0) { 
          document.getElementById("results").innerHTML="";
          return;
        }
        if (window.XMLHttpRequest) {
          xmlhttp=new XMLHttpRequest();
        } else {
          xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function() {
          if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            document.getElementById("results").innerHTML=xmlhttp.responseText;
          }
        }

        xmlhttp.open("GET","lucid-tube.php?username=<?= $username ?>&search="+str,true);
        xmlhttp.send();
      }

      function addFavorite(str) {
        if (str.length==0) { 
          return;
        } else {
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
          xmlhttp.open("GET","lucid-database.php?favorite="+str,true);
          xmlhttp.send();
        }
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
      <input type="search" id="search" name="search" size="100" onkeyup="getResult(this.value)" onmousedown="getResult(this.value)" placeholder="Unesite pojam pretrage" >
    </div>
    <div id="results"></div>
  </body>
</html>
