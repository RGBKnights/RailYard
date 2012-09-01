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
 * JavaScript helper methods
 * 
 */
class JavascriptComponent extends Object {
	public function start_script($options  = array()) {
		ob_start();
	}
	
	public function end_script() {
		$script = ob_get_clean();
		
		$html = new HtmlComponent();
		return $html->script_block($script);
	}
	
	public function serialize($data) {
		 return json_encode($data);
	}
	
	public function deserialize($data) {
		return json_decode($data);
	}
	
	public function event() {
		// return new jsEvent();
		
		/*
		.bind(eventType, [ eventData ], handler(event, ui) )
		.blur([ eventData ], handler(event, ui))
		.change()
		.click()
		.dblclick()
		.delegate()
		.die()
		.error()
		.focus()
		.focusin()
		.focusout()
		.hover()
		.keydown()
		.keypress()
		.keyup()
		.live()
		.load()
		.mousedown()
		.mouseenter()
		.mouseleave()
		.mousemove()
		.mouseout()
		.mouseover()
		.mouseup()
		.one()
		.ready()
		.resize()
		.scroll()
		.select()
		.submit()
		.toggle()
		.trigger()
		.triggerHandler()
		.unbind()
		.undelegate()
		.unload()
		*/
	}

	public function draggable($options = array(), $events = array()) {}
	public function droppable($options = array(), $events = array()) {}
	public function resizable($options = array(), $events = array()) {}
	public function selectable($options = array(), $events = array()) {}
	public function sortable($options = array(), $events = array()) {}

	public function accordion($options = array(), $events = array()) {}
	public function autocomplete($options = array(), $events = array()) {}
	public function button($options = array(), $events = array()) {}
	public function datepicker($options = array(), $events = array()) {}
	public function dialog($options = array(), $events = array()) {}
	public function progressbar($options = array(), $events = array()) {}
	public function slider($options = array(), $events = array()) {}
	public function tabs($options = array(), $events = array()) {}
}