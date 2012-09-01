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
 * Object class.
 * base class for all RailYard objects
 *
 */
abstract class Object {
	/**
	* Object-to-string conversion.
	* Each class can override this method as necessary.
	*
	* @return string The name of this class
	* @access public
	*/
	public function to_string() {
		$class = get_class($this);
		return $class;
	}
	
	/**
	 * Stop execution of the current script.  Wraps exit() making 
	 * testing easier.
	 *
	 * @param $status see http://php.net/exit for values
	 * @return void
	 * @access public
	 */
	public function stop($status = 0) {
		exit($status);
	}
}