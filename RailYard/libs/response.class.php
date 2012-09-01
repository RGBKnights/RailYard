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

class Response extends Object {
	public $processed = false;
	
	public function script($route) {
		if($this->processed)
			return false;
			
		$default['base'] = 'webroot';
		$default['path'] =  'js';
		$default['ext'] = '.js';
		
		if(is_array($route)) {
			$params = array_merge($default, $route);
			
		} else if(is_string($route)) {
			$params = $default;
			$params['file'] = $route;
		}
		
		$this->layout->head->references->scripts[] = Router::static_url($params);
		
		return true;
	}
	
	public function css($route) {
		if($this->processed)
			return false;
			
		$default['base'] = 'webroot';
		$default['path'] =  'css';
		$default['ext'] = '.css';
		
		if(is_array($route)) {
			$params = array_merge($default, $route);
			
		} else if(is_string($route)) {
			$params = $default;
			$params['file'] = $route;
		}
		
		$this->layout->head->references->css[] = Router::static_url($params);
		
		return true;
	}
	
	public function cookie($name, $value, $options = array()) {
		if($this->processed)
			return false;
		
		setcookie($name, $value);
		
		return true;
	}
	
	public function session($name, $value) {
		$_SESSION[$name] = $value;
	}
	
	public function flash($message, $layout = 'flash') {
		$html = View::render($message, $layout, 'layouts');
		
		$_SESSION['_flash'] = $html;
	}
	
	public function redirect($url) {
		if($this->processed)
			return false;
		
		$this->processed = true;
		
		header('Location: ' . $url);
		
		return true;
	}
	
	public function attach($file) {
		if($this->processed)
			return false;
		
		$this->processed = true;
		
		//TODO: handle attachments
		
		return false;
	}
	
	public function render() {
		if($this->processed)
			return false;
			
		$this->processed = true;
		
		$html = View::render($this->layout, $this->layout->name, 'layouts');
		if(class_exists('tidy')) {
			// Tidy
			$tidy = new tidy;
			$tidy->parseString($html, array(
				'indent'			=> true,
				'wrap'				=> 0,
				'output-xhtml'		=> true,
				'show-body-only'	=> false,
				));
			$tidy->cleanRepair();

			// Output
			$html = tidy_get_output($tidy);
		}
		
		echo $html;
		
		return true;
	}
}