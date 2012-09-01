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

class Router extends Singleton {
	
	private $_codes = array(
		100 => 'Continue', 101 => 'Switching Protocols',
		200 => 'OK', 201 => 'Created', 202 => 'Accepted',
		203 => 'Non-Authoritative Information', 204 => 'No Content',
		205 => 'Reset Content', 206 => 'Partial Content',
		300 => 'Multiple Choices', 301 => 'Moved Permanently',
		302 => 'Found', 303 => 'See Other',
		304 => 'Not Modified', 305 => 'Use Proxy', 307 => 'Temporary Redirect',
		400 => 'Bad Request', 401 => 'Unauthorized', 402 => 'Payment Required',
		403 => 'Forbidden', 404 => 'Not Found', 405 => 'Method Not Allowed',
		406 => 'Not Acceptable', 407 => 'Proxy Authentication Required',
		408 => 'Request Time-out', 409 => 'Conflict', 410 => 'Gone',
		411 => 'Length Required', 412 => 'Precondition Failed',
		413 => 'Request Entity Too Large', 414 => 'Request-URI Too Large',
		415 => 'Unsupported Media Type', 416 => 'Requested range out of range',
		417 => 'Expectation Failed', 500 => 'Internal Server Error',
		501 => 'Not Implemented', 502 => 'Bad Gateway',
		503 => 'Service Unavailable', 504 => 'Gateway Time-out'
	);
	
	public function get_status($status) {
		return $this->_codes[$status];
	}
	
	private function parse_extension($path) {
		$result  = strpos($path, ".");
		if($result !== false) {
			return explode('.', $path);
		} else {
			return array($path, '');
		}
	}
	
	public function build_path($request, $paths) {
		$dispatchers_path = Framework::app_path('Dispatchers');
		$dispatchers_suffix = Framework::app_suffix('Dispatchers');
		
		foreach($paths as $path) {	
			
			$directory = $dispatchers_path . implode(DS, $request->url->prefix) . DS . strtolower($path);
			$file = $dispatchers_path . implode(DS, $request->url->prefix) . DS . strtolower($path) . $dispatchers_suffix;
			$default = $dispatchers_path . implode(DS, $request->url->prefix) . DS . "default" . $dispatchers_suffix;
			
			if( empty($path) || strpos($path,':') == true) {
				continue;
			} elseif (file_exists($directory)) {
				$request->url->prefix[] = $path;
			} else if(empty($request->url->controller) && file_exists($file) && !empty($path)) {
				list($name, $ext) = $this->parse_extension($path);
				
				$request->url->controller = $name;
				$request->url->dispatcher = $name;
				$request->url->extension = $ext;
			} else if(empty($request->url->controller) && file_exists($default) && !empty($path)) {
				list($name, $ext) = $this->parse_extension($path);
				
				$request->url->controller = $path;
				$request->url->extension = $ext;
			} else if(!empty($request->url->controller) && empty($request->url->action)) {
				$request->url->action = $path;
			}
		}
		
		$request->url->action = ($request->url->action != '') ? $request->url->action : 'index';
	}
	
	public function build_parameters($parameters) {
		$url = "";
		foreach($parameters as $name => $value) {
			$url .= "/".$name.":".$value;
		}
		$url .= "/";
		return $url;
	}
	
	public function build_query($parameters) {
		$url = "?";
		foreach($parameters as $name => $value) {
			$url .= $name."=".$value."&";
		}
		$url = substr($url, 0, -1);
		return $url;
	}
	
	public function parse_parameters($request, $paths, $query) {
		foreach($paths as $path) {
			if(strpos($path,':') != false) {
				list($key, $value) = explode(':', $path);
				$request->url->parameters->named[$key] = $value;
			}
		}
		
		$paths = explode('&', $query);
		foreach($paths as $path) {
			if(strpos($path,'=') != false) {
				list($key, $value) = explode('=', $path);
				$request->url->parameters->query[$key] = $value;
			}
		}
	}
	
	public static function base_url() {
		$strleft = function ($s1, $s2) {
			return substr($s1, 0, strpos($s1, $s2));
		};
		
		$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
		$protocol = $strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
		$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
		return $protocol."://".$_SERVER['SERVER_NAME'].$port;
	}
	
	public static function full_url() {
		return self::base_url().$_SERVER['REQUEST_URI'];
	}
	
	
	public static function dynamic_url($route = array()) {
		$_this = Router::get_instance();
		
		//self::base_url()
		
		$url = "/index.php/" . implode('/', $route['prefix']) . '/' . $route['controller'] . '/' . $route['action'];
		$url .= (empty($route['parameters'])) ? '' : $_this->build_parameters($route['parameters']);
		$url .= (empty($route['?'])) ? '' : $_this->build_query($route['?']);
		$url .= (empty($route['#'])) ? '' : "#" . $route['#'];
		return $url;
	}
	
	public static function static_url($route = array()) {
		//self::base_url()
		
		$url = "/" . $route['base'] . '/' . $route['path'] . '/' . $route['file'] . $route['ext'];
		return $url;
	}
}