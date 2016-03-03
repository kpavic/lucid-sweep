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

    function search_vids($q, $maxResults){

      $searchResponse = $this->youtube->search->listSearch('id,snippet', 
            ['q' => $q,
            'maxResults' => $maxResults,
            'type' => 'video'
             ]);

      $elements = [];
      $videos = '';

      foreach ($searchResponse['items'] as $searchResult) {
        $elements[$searchResult['id']['videoId']] = [
            'id' => $searchResult['id']['videoId'],
            'title' => $searchResult['snippet']['title'], 
            'thumbnail' => $searchResult['snippet']['thumbnails']['default']['url'],
            'description' => $searchResult['snippet']['description'],
             ];
        $videos .= sprintf('<tr><td>
                                  <p class="favorite" id="%s" onclick="addFavorite(%s)" style="border:1px solid black;">
                                     +<br/>Dodaj u<br/>favorite
                                  </p>
                                </td>
                                <td><a href="play.php?video=%s"><img src="%s"/></a></td>
                                <td><b>%s</b> <br/><pi style="color : grey"> %s </p></td>
                            </tr>',
                           end($elements)['id'], 
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
        $elements[$searchResult['id']['videoId']] = [
        'id' => $searchResult['id']['videoId'],
        'title' => $searchResult['snippet']['title'],    
        'thumbnail' => $searchResult['snippet']['thumbnails']['default']['url'],
        'description' => $searchResult['snippet']['description'],
        ];
      $videos .= sprintf('<tr><td>
                                <p class="favorite" id="%s" onclick="removeFavorite(%s)" style="border:1px solid black;">
                                   -<br/>Ukloni iz<br/>favorita
                                </p>
                              </td>
                              <td><a href="play.php?video=%s"><img src="%s"/></a></td>
                              <td><b>%s</b> <br/><pi style="color : grey"> %s </p></td>
                          </tr>',
                         end($elements)['id'], 
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
$lucid = new LucidTube();

if ($_GET['search']) {
    echo $lucid->search_vids($_GET['search'], '50');
}

?>
