<?php

/**
 * PHP versions 5
 *
 * RailYard(tm) : The tracks of rapid development (http://myrailyard.com)
 * Copyright 2010, RGBKights
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	Copyright 2010, RGBKights. (http://rgbknights.venatiostudios.com)
 * @link		http://myrailyard.com RailYard(tm) Project
 * @since		RailYard(tm) v 0.1.0
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 */ 

/**
 * Controls the application flow
 * 
 */
class RoundHouse extends Object {
	public $request = null;
	public $response = null;
	
	private $_inbound_tracks = array();
	private $_double_tracks = array();
	private $_outbound_tracks = array();
	
	function __construct($inbound, $application, $outbound) {
		$this->request = new Request();
		$this->response = new Response();
		
		foreach($inbound as $track) {
			Framework::import('tracks', $track);
			$track_class = $track."Track";
			
			$this->_inbound_tracks[] = new $track_class();
		}
			
		foreach($application as $track) {
			Framework::import('tracks', $track);
			$track_class = $track."Track";
			
			$this->_double_tracks[] = new $track_class();
		}
	
		foreach($outbound as $track) {
			Framework::import('tracks', $track);
			$track_class = $track."Track";
			
			$this->_outbound_tracks[] = new $track_class();
		}
	}
	
	public function initialize() {
		$tracks = array_merge($this->_inbound_tracks, $this->_double_tracks);
		
		foreach($tracks as $track) {
			$track->initialize($this->request, $this->response);
		}
	}
	
	public function process() {
		$include =  Framework::app_path('dispatchers') . implode(DS, $this->request->url->prefix) . DS . $this->request->url->dispatcher .  Framework::app_suffix('dispatchers');
		$result = @include_once $include;
		
		$class = Inflector::camelize($this->request->url->dispatcher)."Dispatcher";
		
		if($result && class_exists($class)) {
			$dispatcher = new $class();
			$dispatcher->dispatch($this->request, $this->response);
		} else {
			Framework::error("Dispatcher: $class can not be found.");
		}
	}
	
	public function finalize() {
		$tracks = array_merge($this->_double_tracks, $this->_outbound_tracks);
		
		foreach($tracks as $track) {
			$track->finalize($this->request, $this->response);
		}
	}
}
?>
