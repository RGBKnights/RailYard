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
 * Renders a model against a view to generate text.
 * 
 */
class View extends Singleton {
	
	private $_html = null;
	private $_js = null;
	private $_number = null;
	private $_text = null;
	private $_time = null;
	private $_xml = null;
	
	final protected function __construct() {
		$this->_html = new HtmlComponent();
		$this->_js = new JavascriptComponent();
		$this->_number = new NumberComponent();
		$this->_text = new TextComponent();
		$this->_time = new TimeComponent();
		$this->_xml = new XmlComponent();
	}
	
	private function _render($model, $__view, $__path, $__data, $__cached) {
		$__file = Framework::app_path("views") . $__path . DS . $__view . Framework::app_suffix("views");
		
		extract($__data, EXTR_SKIP);
		ob_start();
		
		include ($__file);

		$output = ob_get_clean();
		return $output;
	}

	public static function render($model, $__view, $__path,  $__data = array(), $__cached = false) {
		
		$_this = View::get_instance();
		return $_this->_render($model, $__view, $__path, $__data, $__cached);
	}
	
	public static function Html() {
		$_this = View::get_instance();
		return $_this->_html;
	}
	
	public static function Js() {
		$_this = View::get_instance();
		return $_this->_js;
	}
	
	public static function Number() {
		$_this = View::get_instance();
		return $_this->_number;
	}
	
	public static function Text() {
		$_this = View::get_instance();
		return $_this->_text;
	}
	
	public static function Time() {
		$_this = View::get_instance();
		return $_this->_time;
	}
	
	public static function Xml() {
		$_this = View::get_instance();
		return $_this->_xml;
	}
}