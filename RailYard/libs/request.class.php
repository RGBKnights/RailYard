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
 * @copyright     Copyright 2010, RGBKights. (http://rgbknights.venatiostudios.com)
 * @link          http://myrailyard.com RailYard(tm) Project
 * @since         RailYard(tm) v 0.1.0
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */ 

class Request extends Object {

	public function has_parameter($key) {
		if(!empty($this->url->parameters->named[$key]))
			return true;
		
		if(!empty($this->url->parameters->query[$key]))
			return true;
		
		return false;
	}
	
	public function get_parameter($key) {
		if(!empty($this->url->parameters->named[$key]))
			return $this->url->parameters->named[$key];
			
		if(!empty($this->url->parameters->query[$key]))
			return $this->url->parameters->query[$key];
	}
	
	public function parameter_or_default($key, $default) {
		return $this->has_parameter($key) ? $this->get_parameter($key) : $default;
	}
}

?>