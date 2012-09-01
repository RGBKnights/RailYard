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

class ResponseTrack extends InboundTrack {
	function initialize($request, $response) {
		$response->server = new stdClass();
		$response->server->name = $_SERVER['SERVER_NAME'];
		$response->server->ip = $_SERVER['SERVER_ADDR'];
		$response->accepts = array();
		$response->header = array();
	}
}

?>