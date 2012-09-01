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

class RequestTrack extends InboundTrack {
	function initialize($request, $response) {
		
		$request->time = new stdClass();
		$request->time->start = date('l jS \of F Y h:i:s A', $_SERVER['REQUEST_TIME']);
		$request->time->end = '';
		
		$request->type = new stdClass();
		$request->type->ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') : false;
		$request->type->ssl = isset($_SERVER['HTTPS']) ? ($_SERVER['HTTPS'] == 'on') : false;
		$request->type->xml = false; // $_SERVER["HTTP_ACCEPT"] or file extension parsed by the Router
		$request->type->rss = false;
		$request->type->atom = false;
		$request->type->flash = (preg_match('/^(Shockwave|Adobe) Flash/', $_SERVER['HTTP_USER_AGENT'] ) == 1);
	
		$request->verb = new stdClass();
		$request->verb->name = $_SERVER['REQUEST_METHOD'];
		$request->verb->is_get = (strtolower($_SERVER['REQUEST_METHOD']) == 'get');
		$request->verb->is_post = (strtolower($_SERVER['REQUEST_METHOD']) == 'post'); 
		$request->verb->is_put = (strtolower($_SERVER['REQUEST_METHOD']) == 'put');
		$request->verb->is_delete = (strtolower($_SERVER['REQUEST_METHOD']) == 'delete');
		
		$request->client = new stdClass();
		$request->client->ip = $_SERVER['REMOTE_ADDR'];
		$request->client->browser = $_SERVER['HTTP_USER_AGENT']; // request.client.browser = get_browser()
		$request->client->is_mobile = false;
	
		$request->referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
	}
}