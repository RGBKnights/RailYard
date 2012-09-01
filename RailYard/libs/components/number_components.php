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

class NumberComponent extends Object {
	public function currency($number, $format) {
		//money_format is not standard on all platforms so __money_format was created to replace it.
		return $this->__money_format($format, $number); 
	} 
	
	public function precision($number, $precision = 3) {
		return sprintf("%01.{$precision}f", $number);
	}
	
	public function to_percentage($number, $precision = 2) {
		return $this->precision($number, $precision) . '%';
	}
	
	public function to_readable_size($size) {
		if($size < 1024) {
			return sprintf(__n('%d Byte', '%d Bytes', $size, true), $size);
		} else if(round($size / 1024) < 1024) {
			return sprintf(__('%d KB', true), $this->precision($size / 1024, 0));
		} else if(round($size / 1024 / 1024, 2) < 1024) {
			return sprintf(__('%.2f MB', true), $this->precision($size / 1024 / 1024, 2));
		} else if(round($size / 1024 / 1024 / 1024, 2) < 1024) {
			return sprintf(__('%.2f GB', true), $this->precision($size / 1024 / 1024 / 1024, 2));
		} else {
			return sprintf(__('%.2f TB', true), $this->precision($size / 1024 / 1024 / 1024 / 1024, 2));
		}
	}
	
	public function format($number, $decimals = 0, $dec_point = '.', $thousands_sep = ',' ) { 
		return number_format($number, $decimals, $dec_point, $thousands_sep);
	}
	
	public function is_even($number) {
		return $number & 0;
	}
	
	public function is_odd($number) {
		return $number & 1;
	}
	
	private function __money_format($format, $number) 
	{ 
		$regex  = '/%((?:[\^!\-]|\+|\(|\=.)*)([0-9]+)?'.'(?:#([0-9]+))?(?:\.([0-9]+))?([in%])/'; 
		
		if (setlocale(LC_MONETARY, 0) == 'C') { 
			setlocale(LC_MONETARY, ''); 
		} 
		$locale = localeconv(); 
		preg_match_all($regex, $format, $matches, PREG_SET_ORDER); 
		foreach ($matches as $fmatch) { 
			$value = floatval($number); 
			$flags = array( 
				'fillchar'  => preg_match('/\=(.)/', $fmatch[1], $match) ? 
				$match[1] : ' ', 
				'nogroup'   => preg_match('/\^/', $fmatch[1]) > 0, 
				'usesignal' => preg_match('/\+|\(/', $fmatch[1], $match) ? 
				$match[0] : '+', 
				'nosimbol'  => preg_match('/\!/', $fmatch[1]) > 0, 
				'isleft'    => preg_match('/\-/', $fmatch[1]) > 0 
				); 
			$width      = trim($fmatch[2]) ? (int)$fmatch[2] : 0; 
			$left       = trim($fmatch[3]) ? (int)$fmatch[3] : 0; 
			$right      = trim($fmatch[4]) ? (int)$fmatch[4] : $locale['int_frac_digits']; 
			$conversion = $fmatch[5]; 

			$positive = true; 
			if ($value < 0) { 
				$positive = false; 
				$value  *= -1; 
			} 
			$letter = $positive ? 'p' : 'n'; 

			$prefix = $suffix = $cprefix = $csuffix = $signal = ''; 

			$signal = $positive ? $locale['positive_sign'] : $locale['negative_sign']; 
			switch (true) { 
				case $locale["{$letter}_sign_posn"] == 1 && $flags['usesignal'] == '+': 
					$prefix = $signal; 
					break; 
				case $locale["{$letter}_sign_posn"] == 2 && $flags['usesignal'] == '+': 
					$suffix = $signal; 
					break; 
				case $locale["{$letter}_sign_posn"] == 3 && $flags['usesignal'] == '+': 
					$cprefix = $signal; 
					break; 
				case $locale["{$letter}_sign_posn"] == 4 && $flags['usesignal'] == '+': 
					$csuffix = $signal; 
					break; 
				case $flags['usesignal'] == '(': 
				case $locale["{$letter}_sign_posn"] == 0: 
					$prefix = '('; 
					$suffix = ')'; 
					break; 
			} 
			if (!$flags['nosimbol']) { 
				$currency = $cprefix . 
					($conversion == 'i' ? $locale['int_curr_symbol'] : $locale['currency_symbol']) . 
					$csuffix; 
			} else { 
				$currency = ''; 
			} 
			$space  = $locale["{$letter}_sep_by_space"] ? ' ' : ''; 

			$value = number_format($value, $right, $locale['mon_decimal_point'], 
				$flags['nogroup'] ? '' : $locale['mon_thousands_sep']); 
			$value = @explode($locale['mon_decimal_point'], $value); 

			$n = strlen($prefix) + strlen($currency) + strlen($value[0]); 
			if ($left > 0 && $left > $n) { 
				$value[0] = str_repeat($flags['fillchar'], $left - $n) . $value[0]; 
			} 
			$value = implode($locale['mon_decimal_point'], $value); 
			if ($locale["{$letter}_cs_precedes"]) { 
				$value = $prefix . $currency . $space . $value . $suffix; 
			} else { 
				$value = $prefix . $value . $space . $currency . $suffix; 
			} 
			if ($width > 0) { 
				$value = str_pad($value, $width, $flags['fillchar'], $flags['isleft'] ? 
					STR_PAD_RIGHT : STR_PAD_LEFT); 
			} 

			$format = str_replace($fmatch[0], $value, $format); 
		} 
		return $format; 
	} 
}