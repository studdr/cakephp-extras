<?PHP
// Copyright 2010 Jonathan Bradley
// Simple PHP Datasource that makes use of the Bit.ly API.
// Please note that all returns are converted from JSON to array notation.
// Written by: Jonathan Bradley <jonathan@southfloridacreations.com>
// Last modified: 03.12.2010

App::import('Core', array('Xml', 'HttpSocket'));
class BitlySource extends DataSource {
	
	var $description = "Bit.ly Datasource";
	var $url = 'http://api.bit.ly/';
	var $version = '2.0.1';
	var $params = array();
	var $Http = null;

	function __construct($config) {
		parent::__construct($config);
		$this->Http =& new HttpSocket();
	   $this->params['login'] = $this->config['login'];
	   $this->params['apiKey'] = $this->config['apiKey'];
	}
	
	function expand($shortUrl) {
	   $this->url = $this->url . 'expand?version='. $this->version;
	   $this->params['shortUrl'] = $shortUrl;
	   return $this->__process();
	}
	
	function info($shortUrl) {
	   $this->url = $this->url . 'info?version='. $this->version;
	   $this->params['shortUrl'] = $shortUrl;
	   return $this->__process();
	}
	
	function stats($shortUrl) {
	   $this->url = $this->url . 'stats?version='. $this->version;
	   $this->params['shortUrl'] = $shortUrl;
	   return $this->__process();
	}
	
	function shorten($longUrl) {
	   $this->url = $this->url . 'shorten?version='. $this->version;
	   $this->params['longUrl'] = $longUrl;
	   return $this->__process();
	}
	
	function __process() {
	   $data = $this->Http->get($this->url, $this->params);
	   return json_decode($data);
	}
}
?>