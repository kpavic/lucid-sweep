<?php

  /**
   * Handles database queries for users and their favorites
   *
   * Connects to a local sqlite3 database and handles all queries to it.
   * Tables in the database are: users and favorites.
   */
  class LucidData {
    /**
     * Connects to the local database
     */
    function __construct() {
      $this->database = new SQLite3('db/lucid.db');
    }

    /**
     * Adds a user with given <var>username</var> and <var>password</var> to the users table.
     *
     * Returns a message describing if the action was successful.
     */
    function add_user($username, $password){
      $query = "INSERT INTO users (username, pass, full_name) VALUES ('".$username."', '".$password."', '".$username."');";

      $this->database->exec($query);
      if($this->database->changes()==1){
        $result = "Korisnik uspjesno dodan.";
      } else {
        $result = "Korisnik nije dodan!";
      }

      return $result;
    }

    /**
     * Removes a user with given <var>username</var> and <var>password</var> from the users table.
     *
     * Returns a message describing if the action was successful.
     */
    function remove_user($user_id){
      $query = "DELETE FROM users WHERE id = '".$user_id."';";

      $this->database->exec($query);
      if($this->database->changes()==1){
        $result = "Korisnik uspjesno uklonjen.";
      } else {
        $result = "Korisnik nije uklonjen!";
      }

      return $result;
    }

    /**
     * Retrieves user id for a given <var>username</var> from the users table.
     */
    function get_user_id($username){
      $query = "SELECT id, username, pass FROM users WHERE username = '".$username."';";
      $result = $this->database->query($query);
      $user = $result->fetchArray(SQLITE3_ASSOC);
      $user_id = $user['id'];

      return $user_id;
    }

    /**
     * Checks if user login information is valid for a given <var>username</var> and <var>password</var>.
     * 
     * Checks if user login information is valid for a given <var>username</var> and <var>password</var>.
     * Queries user table for <var>username</var> and verifies against the stored hashed user password.
     *
     * Returns boolean result of the password verification.
     */
    function user_login($username, $password){
      $query = "SELECT id, username, pass FROM users WHERE username = '".$username."';";
      $result = $this->database->query($query);

      $user = $result->fetchArray(SQLITE3_ASSOC);
      $hash = $user['pass'];

      $check = password_verify($password, $hash);

      return $check;
    }


    /**
     * Adds a video id to the favorites table for a given user.
     *
     * Adds a <var>video_id</var> to the favorites table for a given <var>user_id</var>.
     * Echoes "Dodano" if successful.
     */
    function add_to_favorites($user_id, $video_id){
      $query = "INSERT INTO favorites (user_id, video_id) VALUES ('".$user_id."', '".$video_id."');";
      $this->database->exec($query);
      if($this->database->changes()==1){
        echo "Dodano";
      }
    }

    /**
     * Removes a video id from the favorites table for a given user.
     *
     * Removes a <var>video_id</var> from the favorites table for a given <var>user_id</var>.
     * Echoes "Uklonjeno" if successful.
     */
    function remove_from_favorites($user_id, $video_id){
      $query = "DELETE FROM favorites WHERE video_id = '".$video_id."' AND user_id = '".$user_id."';";
      $this->database->exec($query);
      if($this->database->changes()==1){
        echo "Uklonjeno";
      }
    }

    /**
     * Retrieves a csv list of favorite video ids for a given user.
     *
     * Retrieves a csv list of favorite video ids for a given <var>user_id</var>
     * up to a maximum of 50.
     */
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
