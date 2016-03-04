<?php
  set_include_path(get_include_path() . PATH_SEPARATOR . '/usr/src/google-api-php-client/src/');    
  require_once 'Google/autoload.php'; 
  require_once 'Google/Client.php';
  require_once 'Google/Service/YouTube.php';


  class LucidTube {

    function __construct() {
      $this->DEVELOPER_KEY = 'AIzaSyAI5P9HN_9iucCWjOd-AH3j_5CRJ7ljKyQ';
      $this->client = new Google_Client();
      $this->client->setDeveloperKey($this->DEVELOPER_KEY);
      $this->youtube = new Google_Service_YouTube($this->client);
      $this->maxResults = "50";
    }

    function search_vids($q, $maxResults, $user){

      $searchResponse = $this->youtube->search->listSearch('id,snippet', 
            ['q' => $q,
            'maxResults' => $maxResults,
            'type' => 'video'
             ]);

      $elements = [];
      $videos = '';

      if($user != 'None'){
        $addToFavorites = 'onmousedown="addFavorite(this.id)" onclick="addFavorite(this.id)" 
                           style="border:1px solid black;"> +<br/>Dodaj u<br/>favorite';
      } else {
        $addToFavorites = '>';
      }

      foreach ($searchResponse['items'] as $searchResult) {
        $elements[$searchResult['id']['videoId']] = [
            'id' => $searchResult['id']['videoId'],
            'title' => $searchResult['snippet']['title'], 
            'thumbnail' => $searchResult['snippet']['thumbnails']['default']['url'],
            'description' => $searchResult['snippet']['description'],
             ];
        $videos .= sprintf('<tr><td class="favorite" id="%s"'.$addToFavorites.'
                                </td>
                                <td><a href="play.php?video=%s"><img src="%s"/></a></td>
                                <td><b>%s</b> <br/><pi style="color : grey"> %s </p></td>
                            </tr>',
                           end($elements)['id'], end($elements)['id'], 
                           end($elements)['thumbnail'], 
                           end($elements)['title'], 
                           end($elements)['description']
                           );
      }

      $htmlBody = sprintf ('<h3>Results</h3><table><ul>%s</ul></table>', $videos);
      return $htmlBody;

    }

    function get_vid_info($video_id){
      $getSnippet = $this->youtube->videos->listVideos("snippet", array('id' => $video_id));
      $video_title = $getSnippet[0]['snippet']['title'];
      $video_description = $getSnippet[0]['snippet']['description'];

      $htmlBody = sprintf('<iframe width="640" height="480"
                             src="http://www.youtube.com/embed/%s?autoplay=1">
                           </iframe>', $video_id);

      $result = [$video_title, $video_description, $htmlBody];
      return $result;
    }
      
    function get_vid_list($video_ids){
      $videoList = $this->youtube->videos->listVideos("id, snippet", array('id' => $video_ids));
      $videos = '';

      foreach ($videoList['items'] as $searchResult) {
        $elements[$searchResult['id']] = [
        'id' => $searchResult['id'],
        'title' => $searchResult['snippet']['title'],    
        'thumbnail' => $searchResult['snippet']['thumbnails']['default']['url'],
        'description' => $searchResult['snippet']['description'],
        ];
      $videos .= sprintf('<tr><td>
                                <p class="favorite" id="%s" onclick="removeFavorite(this.id)" style="border:1px solid black;">
                                   -<br/>Ukloni iz<br/>favorita
                                </p>
                              </td>
                              <td><a href="play.php?video=%s"><img src="%s"/></a></td>
                              <td><b>%s</b> <br/><pi style="color : grey"> %s </p></td>
                          </tr>',
                         end($elements)['id'], 
                         end($elements)['id'], 
                         end($elements)['thumbnail'], 
                         end($elements)['title'], 
                         end($elements)['description']
                         );

        }
      $htmlBody = sprintf ('<h3>Results</h3><table><ul>%s</ul></table>', $videos);
      return $htmlBody;

    }

  }

session_start();

$lucid = new LucidTube();

if (isset($_GET['search'])) {
    if(isset($_SESSION['user_id'])) {
      $user = $_SESSION['user_id'];
    } else {
      $user = "None";
    }
    echo $lucid->search_vids($_GET['search'], '50', $user);
}

if (isset($_GET['list_favorites']) && isset($_SESSION['user_id'])) {
    include 'lucid-data.php';
    $lucid_data = new LucidData();
    $vid_list = $lucid_data->fetch_favorites($_SESSION['user_id']);

    echo $lucid->get_vid_list($vid_list);
}

?>
