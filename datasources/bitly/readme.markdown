#Overview

A simple Datasource for the Bit.ly standard API. Documentation for the api can be found here: 
http://code.google.com/p/bitly-api/wiki/ApiDocumentation

#Usage

	<?php
      class BitlyController extends AppController {
         
         var $uses = array();
         
         function index() {
            $url = 'http://bit.ly/9FnclE';
            App::Import('ConnectionManager');
            $this->Bitly = ConnectionManager::getDataSource('bitly');
            $response = $this->Bitly->stats($url);
            debug($response); 
         }
      }
	?>

#Contributors
- jonathanbradley
- josegonzalez