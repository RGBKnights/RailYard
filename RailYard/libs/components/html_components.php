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
 * Converts objects to html
 * 
 */
class HtmlComponent extends Object {
	public function tag($tag, $body = null, $attributes = array()) {
		$attrs = "";
		foreach($attributes as $key => $item) {
			$attrs .= "$key=\"$item\" ";
		}
		
		$tag = ($body == null) ? "<$tag $attrs />" : "<$tag $attrs>$body</$tag>";
		return $tag;
	}
	
	public function script($collection) {
		if(is_array($collection)) {
			$tags = array();
			foreach($collection as $item) {
				$tags[] = $this->tag('script', '', array('type' => 'text/javascript', 'src' => $item));
			}
			return implode("\n", $tags);
		} else {
			return $this->tag('script', '', array('type' => 'text/javascript', 'src' => $collection));
		}
	}
	
	public function css($collection) {
		if(is_array($collection)) {
			$tags = array();
			foreach($collection as $item) {
				$tags[] = $this->tag('link', null, array('rel' => 'stylesheet', 'type' => 'text/css', 'href' => $item));
			}
			return implode("\n", $tags);
		} else {
			return $this->tag('link', null, array('rel' => 'stylesheet', 'type' => 'text/css', 'href' => $collection));
		}
	}
	
	public function script_block($collection) {
		if(is_array($collection)) {
			$tags = array();
			foreach($collection as $item) {
				$tags[] = $this->tag('script', $item, array('type' => 'text/javascript'));
			}
			return implode("\n", $tags);
		} else {
			return $this->tag('script', $collection, array('type' => 'text/javascript'));
		}
	}
	
	public function style($collection) {
		if(is_array($collection)) {
			$tags = array();
			foreach($collection as $item) {
				$tags[] = $this->tag('style', $css, array('type' => 'text/css'));
			}
			return implode("\n", $tags);
		} else {
			return $this->tag('style', $collection, array('type' => 'text/css'));
		}
	}
	
	public function div($body = null, $attributes = array()) {
		return $this->tag("div", $body, $attributes);
	}
	
	public function charset($charset) {
		return $this->tag("meta", null, array('http-equiv' => 'Content-Type', 'content' => "text/html; charset={$charset};", ));
	}
	
	public function icon() {
		return $this->tag("link", null, array('href' => '/favicon.ico', 'title' => 'favicon', 'type' => 'image/x-icon'));
	}
	
	public function doc_type($type = 'xhtml-strict') {
		$doc_types = array(
			'html4-strict'  => '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">',
			'html4-trans'  => '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">',
			'html4-frame'  => '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">',
			'xhtml-strict' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">',
			'xhtml-trans' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
			'xhtml-frame' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">',
			'xhtml11' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">'
		);
		
		return $doc_types[$type];
	}
	
	public function image($path, $attributes = array()) {
		$attributes['src'] = $this->url(array('base' => 'webroot','path' => 'img','file' => $path,'ext' => ''), 'static');
		
		return $this->tag('img', null, $attributes);
	}
	
	public function link($title, $route, $attributes = array()) {
		$attributes['href'] = Router::dynamic_url($route);
		
		return $this->tag('a', $title, $attributes);
	}
	
	public function url($route, $type = 'dyanmic') {
		if($type == 'dyanmic') {
			return Router::dynamic_url($route);
		} else if($type == 'static') {
			return Router::static_url($route);
		} else {
			return "";
		}
	}
	
	public function table($columns, $records, $attributes = array()) {
		$cells = array();
		foreach($columns as $column) {
			$cells[] = $this->tag('th', $column);
		}
		$thead = $this->tag('thead', $this->tag('tr', implode($cells)));
		
		$rows = array();
		foreach($records as $index => $record) {
			$cells = array();
			foreach($record as $item) {
				$cells[] = $this->tag('td', $item);
			}
			$rows[] = $this->tag('tr', implode($cells));
		}
		$tbody = $this->tag('tbody', implode($rows));
		
		$table = $this->tag('table', $thead.$tbody, $attributes);
		return $table;
	}
	
	public function unordered_list($data, $list_attributes = array(), $item_attributes = array()) {
		$items = array();
		foreach($data as $item) {
			$items[] = $this->tag('li', $item, $item_attributes);
		}
		
		$list = $this->tag('ul', implode($items), $list_attributes);
		return $list;
	}
	
	public function ordered_list($data, $list_attributes = array(), $item_attributes = array()) {
		$items = array();
		foreach($data as $item) {
			$items[] = $this->tag('li', $item, $item_attributes);
		}
		
		$list = $this->tag('ol', implode($items), $list_attributes);
		return $list;
	}
	
	public function flash($attributes = array()) {
		if(isset( $_SESSION['_flash'])) {
			$html = $_SESSION['_flash'];
			unset($_SESSION['_flash']); 
			
			return $html;
		} else {
			return "";
		}
	}
	
}