<?php
  $htmlBody = '';
  $action = 'add';
  if ($_GET['video']) {
    include 'lucid-tube.php';

    $video_id = $_GET['video'];

    $lucid = new LucidTube();

    $result = $lucid->get_vid_info($video_id);
    $video_title = $result['0'];
    $video_description = $result['1'];
    $htmlBody = $result['2'];

}
?>

<!doctype html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Fake Youtube</title>
    <script>
      function addFavorite(str, action) {
        if (str.length==0) { 
              document.getElementById("addfavorite").innerHTML="+<br/>Dodaj u<br/>favorite";
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
            xmlhttp.open("GET","db_control.php?addfavorite="+str,true);
              xmlhttp.send();
      }
    </script>
  </head>
  <body>
    <div id="navigation">
      <a style="float: left;" href="index.php">Fake Youtube</a>
      <a href="favorites.php">My videos</a>
      <a href="logout.php">Logout</a>
    </div>
    <div id="main_search">
    <?=$htmlBody?>
    <p>
       <b><?= $video_title ?></b> <br/>
       <?= $video_description ?>
    </p>
    <p id="addfavorite" class="favorite" onclick="addFavorite(<?= $video_id ?>, <?= $action ?>)" style="border:1px solid black;">
      +<br/>Dodaj u<br/>favorite
    </p>
    </div>
  </body>
</html>

