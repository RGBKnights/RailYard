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

class LayoutTrack extends InboundTrack {
	function initialize($request, $response) {
		$response->layout = new stdClass();
		
		$response->layout->name = 'default';
		$response->layout->head = new stdClass();
		$response->layout->head->title = "Welcome";
		$response->layout->head->charset = "utf-8";
		
		$response->layout->head->references = new stdClass(); 
		$response->layout->head->references->scripts = array();
		$response->script('jquery-1.4.4.min');
		$response->script('jquery-ui-1.8.9.min');
		
		$response->layout->head->references->css = array();
		//$response->css('reset');
		$response->css('site');
		$response->css('start/jquery-ui-1.8.9');
		
		$response->layout->head->scripts = array();
		$response->layout->head->styles = array();
		
		$response->layout->body = new stdClass();
		$response->layout->body->header = new stdClass();
		$response->layout->body->header->title = "RailYard - The tracks of rapid development";
		$response->layout->body->header->menu = '';
		$response->layout->body->content = "";
		$response->layout->body->footer = "";
	}

	function finalize($request, $response) {
		$response->render();
	}
}