<?php

  class LucidData {
      function __construct() {
        $this->database = new SQLite3('db/lucid.db'); ;
      }

      function get_user_id($username){
        $query = "SELECT id, username, pass FROM users WHERE username = '".$username."';";
        $result = $this->database->query($query);
        $user = $result->fetchArray(SQLITE3_ASSOC);
        $user_id = $user['id'];

        return $user_id;
      }

      function user_login($username, $password){
        $query = "SELECT id, username, pass FROM users WHERE username = '".$username."';";
        $result = $this->database->query($query);

        $user = $result->fetchArray(SQLITE3_ASSOC);
        $hash = $user['pass'];

        $check = password_verify($password, $hash);

        return $check;
      }

    function add_to_favorites($user_id, $video_id){
      $query = "INSERT INTO favorites (user_id, video_id) VALUES ('".$user_id."', '".$video_id."');";
      $this->database->exec($query);
      if($this->database->changes()==1){
        echo "Dodano";
      }
    }

    function remove_from_favorites($user_id, $video_id){
      $query = "DELETE FROM favorites WHERE video_id = '".$video_id."' AND user_id = '".$user_id."';";
      $this->database->exec($query);
      if($this->database->changes()==1){
        echo "Uklonjeno";
      }
    }

    function fetch_favorites($user_id){
      $videos = '';
      $query = "SELECT video_id FROM favorites WHERE user_id = '".$user_id."';";
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

session_start();

if (isset($_GET['favorite']) && isset($_SESSION['user_id'])) {
    $lucid_data = new LucidData();
    $result = $lucid_data->add_to_favorites($_SESSION['user_id'], $_GET['favorite']);
}
if (isset($_GET['unfavorite']) && isset($_SESSION['user_id'])) {
    $lucid_data = new LucidData();
    $result = $lucid_data->remove_from_favorites($_SESSION['user_id'], $_GET['unfavorite']);
}

?>
