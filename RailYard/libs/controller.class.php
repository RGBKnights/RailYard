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

class Controller extends Object {
	
	private $_data = array();
	protected $request = null;
	protected $response = null;
	
	function __construct($request, $response) {
		$this->request = $request;
		$this->response = $response;
	}
	
	public function before_action($request, $response) {
	}
	
	public function after_action($request, $response) {
	}

	protected function redirect($route, $clean = false)  {
		
		$default = array();
		
		if(!$clean) {
			$default['prefix'] = $this->request->url->prefix;
			$default['controller'] = $this->request->url->controller;
			$default['action'] = $this->request->url->action;
			$default['parameters'] = $this->request->url->parameters->named;
			$default['?'] = $this->request->url->parameters->query;
			$default['#'] = $this->request->url->anchor;
		}
		
		$params = array_merge($default, $route);
		
		$url = Router::dynamic_url($params);
		$this->response->redirect($url);
	}

	protected function set($key, $value) {
		$this->_data[$key] = $value;
	}

	protected function render_for_action($model, $type = 'html') {
		return View::render($model, $this->request->url->action, $this->request->url->controller . DS . $type . DS, $this->_data);
	}
	
	protected function script_for_action() {
		$route = array();
		$route['base'] = 'views';
		$route['path'] =  $this->request->url->controller . '/'. 'scripts';
		$route['file'] =  $this->request->url->action;
		$route['ext'] = '.js';

		$url = Router::static_url($route);
		return $url;
	}
	
	protected function script_for_controller() {
		$route = array();
		$route['base'] = 'views';
		$route['path'] =  $this->request->url->controller;
		$route['file'] =  $this->request->url->controller;
		$route['ext'] = '.js';

		$url = Router::static_url($route);
		return $url;
	}
	
	function __call($name, $arguments) {
		Framework::error('Controller can not be found.');
	}
}

