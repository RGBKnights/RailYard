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

class Dispatcher extends Object {
	
	protected function initialize_controller($request, $response, $class, $action) {
		$controller = new $class($request, $response);
		$controller->before_action($request, $response);
		$controller->$action();
		$controller->after_action($request, $response);
	}
	
	public function dispatch($request, $response) {
		$class = Inflector::camelize($request->url->controller)."Controller";
		$action = $request->url->action;
				
		$include = Framework::app_path('controllers') . implode(DS, $request->url->prefix) . DS . $request->url->controller . Framework::app_suffix('controllers');
		$result = @include_once $include;

		if($result && class_exists($class))	 {
			$this->initialize_controller($request, $response, $class, $action);
		} else {
			Framework::error('Controller can not be found.');
		}
	}
	
	
}
