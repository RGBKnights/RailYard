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

class RouteTrack extends InboundTrack {
	function initialize($request, $response) {
		
		$router = Router::get_instance();
		
		$request->url = new stdClass();
		$request->url->full = $router->full_url();
		$request->url->prefix = array();
		$request->url->dispatcher = 'default';
		$request->url->controller = '';
		$request->url->extension = '';
		$request->url->action = '';
		$request->url->parameters = new stdClass();
		$request->url->parameters->named = array();
		$request->url->parameters->query = array();
		$request->url->anchor = '';
		
		$uri = substr(str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['REQUEST_URI']), 1);
		list($path, $query) = (strpos($uri,'?') == false) ? array($uri, '') : explode('?', $uri);
		
		$paths = explode('/', $path);
		$router->build_path($request, $paths);
		$router->parse_parameters($request, $paths, $query);
	}
}

?>