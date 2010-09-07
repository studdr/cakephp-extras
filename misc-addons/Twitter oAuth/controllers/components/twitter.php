<?php

App::import('Model', 'User');
class TwitterComponent extends Object {
   
   var $components = array('Session', 'Auth');
   var $tweet = '';
   var $user = array();
   var $url = '';
   var $Http;
   
   function startup () {
      App::import('Vendor', 'HttpSocketOauth');
      $this->Http = new HttpSocketOauth();
   }
   
   
   function post($status, $category, $url) {
     $status = str_replace(
          array(':', '-', '+', '"', '_', '&#039;', '&quot;', '(', ')', '?', '!', '   ', '.', ';', "'", "'", "'", ',', '#', '@', '/', ' <', '<', '>', '*', '[', ']', '|'), 
          '', 
          $status
         );
      $status = str_replace(array("\n", "\t", "\r"), '', $status);
      $this->tweet = $status;
      $this->category = $category;
      $this->url = $url;
      $this->twitter = $this->Auth->user('twitter');
      if (!empty($this->twitter)) self::__user_tweet();
      self::__default_tweet();
      return $this->url;
   }
   
   function __default_tweet() {
      $request = array(
         'method' => 'POST',
         'uri' => array(
            'host' => 'api.twitter.com',
            'path' => '1/statuses/update.json',
         ),
         'auth' => array(
            'method' => 'OAuth',
            'oauth_token' => Configure::read('Twitter.oauth_token'), 
            'oauth_token_secret' => Configure::read('Twitter.oauth_token_secret'),
            'oauth_consumer_key' => Configure::read('Twitter.consumer_key'),
            'oauth_consumer_secret' => Configure::read('Twitter.consumer_secret'),
         ),
         'body' => array(
            'status' => 'Posted by: '. $this->Auth->user('username') .' in '. ucwords(strtolower($this->category)) .' - '. substr($this->tweet, 0, 75) .' - '. $this->url,
         ),
      );
      return $this->Http->request($request);
   }
   
   function __user_tweet() {
      $request = array(
         'method' => 'POST',
         'uri' => array(
            'host' => 'api.twitter.com',
            'path' => '1/statuses/update.json',
         ),
         'auth' => array(
            'method' => 'OAuth',
            'oauth_token' => $this->Auth->user('oauth_token'), 
            'oauth_token_secret' => $this->Auth->user('oauth_token_secret'),
            'oauth_consumer_key' => Configure::read('Twitter.consumer_key'),
            'oauth_consumer_secret' => Configure::read('Twitter.consumer_secret'),
         ),
         'body' => array(
            'status' => 'Posted in '. ucwords(strtolower($this->category)) .' - '. substr($this->tweet, 0, 90) .' - '. $this->url,
         ),
      );
      return $this->Http->request($request);
   }
   
   function __format($input) {
      $output = html_entity_decode($input);
      $output = str_replace(array('<', '>', '[', ':', ']', '%40', '\''), '', $output);
      $output = preg_replace('/[0-9A-Z]{3,6}\s[A-Z]{2}/', '', $output);
      return str_replace('&nbsp; ', '&nbsp;&nbsp;', $output);
   }
}
?>