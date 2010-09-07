<?php

class TwitterController extends AppController {
   
   var $name = 'Twitter';
   var $uses = array();
   var $Http;
   
   function beforeFilter() {
     
      App::import('Lib', 'HttpSocketOauth');
      $this->Http = new HttpSocketOauth();
   }
   
   
   public function connect() {
      $request = array(
         'uri' => array(
            'host' => 'api.twitter.com',
            'path' => '/oauth/request_token',
         ),
         'method' => 'GET',
         'auth' => array(
            'method' => 'OAuth',
            'oauth_callback' => 'http://studdr.com/twitter/callback',
            'oauth_consumer_key' => Configure::read('Twitter.consumer_key'),
            'oauth_consumer_secret' => Configure::read('Twitter.consumer_secret'),
         ),
      );
      $response = $this->Http->request($request);
      // Redirect user to twitter to authorize  my application
      parse_str($response, $response);
      $this->redirect('http://api.twitter.com/oauth/authorize?oauth_token=' . $response['oauth_token']);
   }
      
   function callback() {
      $request = array(
         'uri' => array(
            'host' => 'api.twitter.com',
            'path' => '/oauth/access_token',
         ),
         'method' => 'POST',
         'auth' => array(
            'method' => 'OAuth',
            'oauth_consumer_key' => Configure::read('Twitter.consumer_key'),
            'oauth_consumer_secret' => Configure::read('Twitter.consumer_secret'),
            'oauth_token' => $this->params['url']['oauth_token'],
            'oauth_verifier' => $this->params['url']['oauth_verifier'],
         ),
      );
      $response = $this->Http->request($request);
      
      parse_str($response, $response);
      // Save data in $response to database or session as it contains the access token and access token secret that you'll need later to interact with the twitter API
      $this->Session->write('Twitter', $response);
      $this->redirect(array('controller' => 'users', 'action' => 'twitter'), null, false);
   }

}
?>