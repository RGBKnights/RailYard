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

class XmlComponent extends Object {
	public function serialize($data) {
		/*$serializer = new XML_Serializer();
		if ($serializer->serialize($data)) {
			return $serializer->getSerializedData();
		}
		else {
			return null;
		}*/
	}
	
	public function deserialize($data) {
		/*$serializer = new XML_Serializer();
		if ($serializer->unserialize($data)) {
			return $serializer->getUnserializedData();
		}
		else {
			return null;
		}*/
	}
	
	public function header($attributes = array()) {
		$default = array(
			'version' => '1.0',
			'encoding' => 'UTF-8',
		);
		
		$attributes = array_merge($default, $attributes);
		foreach($attributes as $key => $item) {
			$attrs .= "$key=\"$item\" ";
		}
		
		return "<?xml $attrs ?>";
	}
}