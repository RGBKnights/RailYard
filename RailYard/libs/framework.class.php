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

include_once "singleton.class.php";
include_once "inflector.class.php";

/**
 * Class/file loader and path management.
 *
 */
class Framework extends Singleton  {
	
	/**
	 * List of object types and their properties
	 *
	 * @var array
	 * @access public
	 */

	var $core_types = array();
	var $app_types = array();
	
	final protected function __construct() {
		$this->core_types = array(
			'libs' => array('suffix' => '.class.php', 'path' => FRAMEWORK_PATH . 'libs' . DS),
			'activerecord' => array('suffix' => '.class.php', 'path' => FRAMEWORK_PATH . 'libs' . DS . 'activerecord' . DS),
			'components' => array('suffix' => '_components.php', 'path' => FRAMEWORK_PATH . 'libs' . DS . 'components' . DS),
			'tracks' => array('suffix' => '_track.php', 'path' => FRAMEWORK_PATH . 'libs' . DS . 'tracks' . DS),
		);
		
		$this->app_types = array(
			'configuration' => array('suffix' => '.php', 'path' => APP_PATH . DS . 'config' . DS),
			'components' => array('suffix' => '_components.php', 'path' => APP_PATH . DS . 'components' . DS),
			'controllers' => array('suffix' => '_controller.php', 'path' => APP_PATH . DS . 'controllers' . DS),
			'dispatchers' => array('suffix' => '_dispatcher.php', 'path' => APP_PATH . DS . 'dispatchers' . DS),
			'models' => array('suffix' => '.php', 'path' => APP_PATH . DS . 'models' . DS),
			'tracks' => array('suffix' => '_track.php', 'path' => APP_PATH . DS . 'tracks' . DS),
			'views' => array('suffix' => '.php', 'path' => APP_PATH . DS . 'views' . DS),
			'webroot' => array('suffix' => '', 'path' =>  APP_PATH . DS . 'webroot' . DS),
		);
	}
		
	/**
	* Used to read information stored path
	*
	* Usage: Framework::core_path('components'); will return core path to components
	*
	* @param string $type type of path
	* @return string
	* @access public
	*/
	public static function core_path($type) {
		$_this = Framework::get_instance();
		return $_this->core_types[strtolower($type)]['path'];
	}
	
	/**
	* Used to read information stored path
	*
	* Usage: Framework::app_path('components'); will return app path to components
	*
	* @param string $type type of path
	* @return string
	* @access public
	*/
	public static function app_path($type) {
		$_this = Framework::get_instance();
		return $_this->app_types[strtolower($type)]['path'];
	}
	
	/**
	* Used to read information stored core suffix
	* @param string $type type of suffix
	* @return string
	* @access public
	*/
	public static function core_suffix($type) {
		$_this = Framework::get_instance();
		return $_this->core_types[strtolower($type)]['suffix'];
	}
	
	/**
	* Used to read information stored app suffix
	* @param string $type type of suffix
	* @return string
	* @access public
	*/
	public static function app_suffix($type) {
		$_this = Framework::get_instance();
		return $_this->app_types[strtolower($type)]['suffix'];
	}

	/**
	 * Finds classes based on $name or specific file(s) to search.
	 *
	 * @param mixed $type_name The type of Class if passed as a string
	 * @param string $name Name of the Class or a unique name for the file
	 * @param string $path path to append to base directory
	 * @param string $file full name of the file to include
	 * @return bool true if Class is already in memory or if file is found and loaded, false if not
	 * @access public
	 */
	public static function import($type_name, $name = null, $file = null) {
					
		$_this = Framework::get_instance();
		$type_name = strtolower($type_name);
		$type = null;
		
		foreach($_this->core_types as $core => $paramaters) {
			if($core == $type_name) {
				$type = $paramaters;
				
				$directory = $type['path'];
				$file = ($file == null) ? Inflector::underscore($name) . $type['suffix'] : $file;
				$path = $directory . $file;
				
				if(file_exists($path)) {
					include $path; 
					return true;
				}
			}
		}
		
		foreach($_this->app_types as $app => $paramaters) {
			if($app == $type_name) {
				$type = $paramaters;
				$directory = $type['path'];
				$file = ($file == null) ? Inflector::underscore($name) . $type['suffix'] : $file;
				$path = $directory . $file;
				
				if(file_exists($path)) {
					include $path; 
					return true;
				}
			}
		}
		
		return false;
	}
	
	/**
	 * Convenience method to write a message to log.
	 *
	 * @param string $message Log message
	 * @param integer $type Error type constant. Defined in /config/core.php.
	 * @return bool Success of log write
	 * @access public
	 */
	public static function log($message, $type = LOG_ERROR) {
		// TODO: Add Log Class
		
		$message = (!is_string($message)) ? print_r($message, true) : $message;
		return Log::write($type, $message);
		return false;
	}
	
	/**
	* Used to report user friendly errors.
		
	* @param array $messages Message that is to be displayed by the error class
	* @return error message
	* @access public
	*/
	public static function error($messages = array()) {
		// TODO: Add ErrorHandler Class
		
		//$error = new ErrorHandler($messages);
		//return $error;
	}

	public static function process_request() {
		
		// Load configuration files
		self::import('libs', "Configure");
		self::import('configuration', "Core", "core.php");

		// Load core libraries
		self::import('libs', "Request");
		self::import('libs', "Response");
		self::import('libs', "String");
		self::import('libs', "Router");
		self::import('libs', "Dispatcher");
		self::import('libs', "Controller");
		self::import('libs', "View");
		self::import('libs', "Queryable");
		self::import('libs', "RoundHouse");
		
		// Load ActiveRecord
		
		self::import('libs', "ActiveRecord");
		$connections = array(
		   'development' => 'mysql://username:password@localhost/development',
		   'production' => 'mysql://username:password@localhost/production',
		   'test' => 'mysql://username:password@localhost/test'
		);
		
		/*
		ActiveRecord\Config::initialize(function($cfg) use ($connections)
		{
			$cfg->set_model_directory('/path/to/your/model_directory');
			$cfg->set_connections($connections);
			
			$cfg->set_default_connection('production');
		});
		*/
		
		// Load Components
		self::import('components', "Html");
		self::import('components', "Javascript");
		self::import('components', "Number");
		self::import('components', "Text");
		self::import('components', "Time");
		self::import('components', "Xml");
		// Add Lazy loading for components through controller or dispatcher
		
		// Load Tracks
		self::import('tracks', "Inbound");
		self::import('tracks', "Outbound");
		self::import('tracks', "Double");
		
		$inbound = array("Body", "Cookie", "Request", "Response", "Route", "Session");
		$application = array("Layout");
		$outbound = array();
		// Read tracks from configuration and import those as well
		
		// Kickoff roundhouse
		$round_house = new RoundHouse($inbound, $application, $outbound);
		$round_house->initialize();
		$round_house->process();
		$round_house->finalize();
		
		//X-HTTP-Method-Override
		
		/*
		echo "<pre>";
		print_r($round_house->request, false);
		print_r($round_house->response, false);
		echo "</pre>";
		*/
	}
	
}