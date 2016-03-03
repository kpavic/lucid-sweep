<?php
  class LucidData {
      function __construct() {
        $this->database = sqlite_open('lucid.db');
      }

      function user_login($username, $password){
        $query = "SELECT id, username, password FROM users WHERE username = ".$username.";";
        $result = $this->database->query($query);

        $user = $result->fetchArray(SQLITE3_ASSOC);
        $hash = $user[0]['pass'];

        if (password_verify($password, $hash)){
          return TRUE;
        } else {
          return FALSE;
        }
      }

    function add_to_favorites($user_id, $video_id){
      $query = "INSERT INTO favorites (user_id, video_id) VALUES (".$user_id." ".$video_id.")";
      $this->database->exec($query);
    }

    function remove_from_favorites($user_id, $video_id){
      $query = "DELETE FROM favorites WHERE video_id = ".$video_id." AND user_id = ".$user_id;
      $this->database->exec($query);
    }

    function fetch_favorites($user_id){
      $videos = '';
      $query = "SELECT video_id FROM favorites WHERE user_id = ".$user_id.";";
      $result = $this->database->query($query);
      $i = 1;

      while($res = $result->fetchArray(SQLITE3_ASSOC)){
        if($i < 50){
          $videos .= $res['video_id'].",";
        }
        $i++;
      }

      return $videos;
    }
  }
?>
