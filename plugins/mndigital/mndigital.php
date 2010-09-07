<?php
/**
 * This is a component for MN Digital. This component has the ability 
 * to fetch the Search.GetAlbums method in the Open API from MN Digital
 * (http://mndigital.com)
 *
 * PHP versions 4 and 5
 *
 * Developed By, Jonathan Bradley (http://itsjonbradley.com)
 * Copyright 2010, Afzet, Inc. (http://afzetinc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * Example Usage:
 * <?php
 * class TestsController extends AppController {
 * var $components = array('Mndigital');
 * function index() {
 * $options = array('artist' => 'Novel Hooligan', 'method' => 'Search.GetAlbums');
 * $data = $this->Mndigital->request($options);
 * $this->set('data', $data);
 * }
 * }
 * ?>
 *
 * Issues can emailed to jonathan@studdr.com
 *
 * @copyright     Copyright 2010, Afzet, Inc. (http://afzetinc.com)
 * @author        Jonathan Bradley (jonathan@studdr.com)
 * @link          http://jonbradley.me
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::import('Core', array('Set', 'Xml', 'HttpSocket'));

class MndigitalComponent extends Object {
  
  // component name
  var $name = 'mndigital';
  // component description
  var $description = 'MN Digital Open API';
  // api secret
  var $secret = 'H86jJrRZAnU';
  // url to post request
  var $url = 'http://ie-api.mndigital.com';
  
  var $defaults = array(
    'apiKey' => 'ykvbwtC3UN8O9qVBABoTFnJ5n',    // api key
    'rights' => 'purchase',                     // what rights are you granting
    'includeExplicit' => 'true',                // allow explicit lyrics
    'mainArtistOnly' => 'false',                // show only main artist (true|false)
    'format' => 'xml'                           // allowed response format xml
  );
  
  /**
   * Process the request from MN Digital
   * @param $options an array of the function and search request
   * @return $data a formated array converting the xml response to an object to an array
   */
  public function request ($options = array()) {

    // fetch respponse
    $this->HttpSocket = new HttpSocket();
    $response = $this->HttpSocket->get($this->url, array_merge($options, $this->defaults));
    
    // convert xml to object
    $xml = new Xml($response);
    
    // convert object to array
    $this->results = Set::reverse($xml);

    // format the results
    foreach ($this->results['AlbumSearchResults']['Albums'] as $key => $value) {
      $data[$key]['mnid']     = $value['MnetId'];
      $data[$key]['artist']   = $value['Artist']['Name'];
      $data[$key]['genre']    = $value['Genre'];
      $data[$key]['explicit'] = $value['ExplicitLyrics'];
      $data[$key]['date']     = $value['ReleaseDate'];
      $data[$key]['tracks']   = $value['NumberOfTracks'];
      $data[$key]['length']   = $value['Duration'];
      $data[$key]['name']     = $value['Title'];
      $data[$key]['media']    = $value['Images'];
      foreach ($value['Tracks']['Track'] as $key2 => $value2) {
        $data[$key]['track'][$key2]['name']   = $value2['Title'];
        $data[$key]['track'][$key2]['artist'] = $value2['Artist']['Name'];
        $data[$key]['track'][$key2]['length'] = $value2['Duration'];
        $data[$key]['track'][$key2]['mnid']   = $value2['MnetId'];
        $data[$key]['track'][$key2]['track']  = $value2['TrackNumber'];
        $data[$key]['track'][$key2]['sample'] = $value2['SampleLocations']['MediaLocation'];
      }
    }      
    return $data;
  }
}
