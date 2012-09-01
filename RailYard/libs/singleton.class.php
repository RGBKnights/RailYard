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

include_once "object.class.php";

/**
 * An implementation of the singleton pattern.
 *
 */
abstract class Singleton extends Object {
	
	private static $_instances = array();
	
	/**
	 * Singleton objects should not publicly constructed.
	 *
	 * @return void
	 */
	protected function __construct() {}
	
	/**
	 * Singleton objects should not be cloned.
	 *
	 * @return void
	 */
	final private function __clone() {}
		
	/**
	* Used to read singleton
	* @return object
	* @access public
	*/	
	final public static function get_instance()
	{
		$class_name = get_called_class();

		if (!isset(self::$_instances[$class_name]))
			self::$_instances[$class_name] = new $class_name;

		return self::$_instances[$class_name];
	}
	
	
	/**
	 * Similar to a get_called_class() for a child class to invoke.
	 *
	 * @return string
	 */
	final protected function get_called_class()
	{
		$backtrace = debug_backtrace();
		return get_class($backtrace[2]['object']);
	}
	
}