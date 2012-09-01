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
 * Configuration class (singleton). Used for managing runtime configuration information.
 * 
 * @see			CakePHP - Configure
 */
class Configure extends Singleton {
	
	/**
	* Current debug level.
	*
	* @var integer
	* @access public
	*/
	var $debug = 0;
	
	/**
	 * Used to store a dynamic variable in the Configure instance.
	 *
	 * Usage:
	 * {{{
	 * Configure::write('One.key1', 'value of the Configure::One[key1]');
	 * Configure::write(array('One.key1' => 'value of the Configure::One[key1]'));
	 * Configure::write('One', array(
	 *     'key1' => 'value of the Configure::One[key1]',
	 *     'key2' => 'value of the Configure::One[key2]'
	 * );
	 *
	 * Configure::write(array(
	 *     'One.key1' => 'value of the Configure::One[key1]',
	 *     'One.key2' => 'value of the Configure::One[key2]'
	 * ));
	 * }}}
	 *
	 * @param array $config Name of var to write
	 * @param mixed $value Value to set for var
	 * @return boolean True if write was successful
	 * @access public
	 */
	public static function write($config, $value = null) {
		$_this = Configure::get_instance();

		
		return true;
	}

	/**
	 * Used to read information stored in the Configure instance.
	 *
	 * Usage:
	 * {{{
	 * Configure::read('Name'); will return all values for Name
	 * Configure::read('Name.key'); will return only the value of Configure::Name[key]
	 * }}}
	 *
	 * @param string $var Variable to obtain.  Use '.' to access array elements.
	 * @return string value of Configure::$var
	 * @access public
	 */
	public static function read($key) {
		$_this = Configure::get_instance();
		
		if($key == 'debug')
			return 2;

		return null;
	}

	/**
	 * Used to delete a variable from the Configure instance.
	 *
	 * Usage:
	 * {{{
	 * Configure::delete('Name'); will delete the entire Configure::Name
	 * Configure::delete('Name.key'); will delete only the Configure::Name[key]
	 * }}}
	 * @param string $var the var to be deleted
	 * @return void
	 * @access public
	 */
	public static function delete($var = null) {
		$_this = Configure::get_instance();

	}
	
}
?>