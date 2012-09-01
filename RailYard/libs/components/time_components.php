<?php

/**
 * PHP versions 5
 *
 * RailYard(tm) : The tracks of rapid development (http://myrailyard.com)
 * Copyright 2010 {} public function  RGBKights
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	Copyright 2010 {} public function  RGBKights. (http://rgbknights.venatiostudios.com)
 * @link		http://myrailyard.com RailYard(tm) Project
 * @since		RailYard(tm) v 0.1.0
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 */ 

class TimeComponent extends Object {
	public function from_string($dateString) {
		if (empty($dateString)) {
			return false;
		}
		if (is_integer($dateString) || is_numeric($dateString)) {
			$date = intval($dateString);
		} else {
			$date = strtotime($dateString);
		}
		if ($date === -1) {
			return false;
		}
		return $date;
	}
	
	public function to_quarter($dateString, $range = false) {
		$time = $this->fromString($dateString);
		$date = ceil(date('m', $time) / 3);

		if ($range === true) {
			$range = 'Y-m-d';
		}

		if ($range !== false) {
			$year = date('Y', $time);

			switch ($date) {
				case 1:
					$date = array($year.'-01-01', $year.'-03-31');
					break;
				case 2:
					$date = array($year.'-04-01', $year.'-06-30');
					break;
				case 3:
					$date = array($year.'-07-01', $year.'-09-30');
					break;
				case 4:
					$date = array($year.'-10-01', $year.'-12-31');
					break;
			}
		}
		return $date;
	}
	
	public function to_atom($dateString) {
		$date = $this->fromString($dateString);
		return date('Y-m-d\TH:i:s\Z', $date);
	}
	
	public function to_rss($dateString) {
		$date = $this->fromString($dateString);
		return date("r", $date);
	}
	
	public function nice($dateString = null) {
		if ($dateString != null) {
			$date = $this->fromString($dateString);
		} else {
			$date = time();
		}
		$format = $this->convertSpecifiers('%a, %b %eS %Y, %H:%M', $date);
		return strftime($format, $date);
	}
	
	public function time_ago_in_words($dateTime, $options = array()) {
		$now = time();
		$inSeconds = $this->fromString($dateTime);
		$backwards = ($inSeconds > $now);

		$format = 'j/n/y';
		$end = '+1 month';

		if (is_array($options)) {
			if (isset($options['format'])) {
				$format = $options['format'];
				unset($options['format']);
			}
			if (isset($options['end'])) {
				$end = $options['end'];
				unset($options['end']);
			}
		} else {
			$format = $options;
		}

		if ($backwards) {
			$futureTime = $inSeconds;
			$pastTime = $now;
		} else {
			$futureTime = $now;
			$pastTime = $inSeconds;
		}
		$diff = $futureTime - $pastTime;

		// If more than a week, then take into account the length of months
		if ($diff >= 604800) {
			$current = array();
			$date = array();

			list($future['H'], $future['i'], $future['s'], $future['d'], $future['m'], $future['Y']) = explode('/', date('H/i/s/d/m/Y', $futureTime));

			list($past['H'], $past['i'], $past['s'], $past['d'], $past['m'], $past['Y']) = explode('/', date('H/i/s/d/m/Y', $pastTime));
			$years = $months = $weeks = $days = $hours = $minutes = $seconds = 0;

			if ($future['Y'] == $past['Y'] && $future['m'] == $past['m']) {
				$months = 0;
				$years = 0;
			} else {
				if ($future['Y'] == $past['Y']) {
					$months = $future['m'] - $past['m'];
				} else {
					$years = $future['Y'] - $past['Y'];
					$months = $future['m'] + ((12 * $years) - $past['m']);

					if ($months >= 12) {
						$years = floor($months / 12);
						$months = $months - ($years * 12);
					}

					if ($future['m'] < $past['m'] && $future['Y'] - $past['Y'] == 1) {
						$years --;
					}
				}
			}

			if ($future['d'] >= $past['d']) {
				$days = $future['d'] - $past['d'];
			} else {
				$daysInPastMonth = date('t', $pastTime);
				$daysInFutureMonth = date('t', mktime(0, 0, 0, $future['m'] - 1, 1, $future['Y']));

				if (!$backwards) {
					$days = ($daysInPastMonth - $past['d']) + $future['d'];
				} else {
					$days = ($daysInFutureMonth - $past['d']) + $future['d'];
				}

				if ($future['m'] != $past['m']) {
					$months --;
				}
			}

			if ($months == 0 && $years >= 1 && $diff < ($years * 31536000)) {
				$months = 11;
				$years --;
			}

			if ($months >= 12) {
				$years = $years + 1;
				$months = $months - 12;
			}

			if ($days >= 7) {
				$weeks = floor($days / 7);
				$days = $days - ($weeks * 7);
			}
		} else {
			$years = $months = $weeks = 0;
			$days = floor($diff / 86400);

			$diff = $diff - ($days * 86400);

			$hours = floor($diff / 3600);
			$diff = $diff - ($hours * 3600);

			$minutes = floor($diff / 60);
			$diff = $diff - ($minutes * 60);
			$seconds = $diff;
		}
		$relativeDate = '';
		$diff = $futureTime - $pastTime;

		if ($diff > abs($now - $this->fromString($end))) {
			$relativeDate = sprintf(__('on %s',true), date($format, $inSeconds));
		} else {
			if ($years > 0) {
				// years and months and days
				$relativeDate .= ($relativeDate ? ', ' : '') . $years . ' ' . __n('year', 'years', $years, true);
				$relativeDate .= $months > 0 ? ($relativeDate ? ', ' : '') . $months . ' ' . __n('month', 'months', $months, true) : '';
				$relativeDate .= $weeks > 0 ? ($relativeDate ? ', ' : '') . $weeks . ' ' . __n('week', 'weeks', $weeks, true) : '';
				$relativeDate .= $days > 0 ? ($relativeDate ? ', ' : '') . $days . ' ' . __n('day', 'days', $days, true) : '';
			} elseif (abs($months) > 0) {
				// months, weeks and days
				$relativeDate .= ($relativeDate ? ', ' : '') . $months . ' ' . __n('month', 'months', $months, true);
				$relativeDate .= $weeks > 0 ? ($relativeDate ? ', ' : '') . $weeks . ' ' . __n('week', 'weeks', $weeks, true) : '';
				$relativeDate .= $days > 0 ? ($relativeDate ? ', ' : '') . $days . ' ' . __n('day', 'days', $days, true) : '';
			} elseif (abs($weeks) > 0) {
				// weeks and days
				$relativeDate .= ($relativeDate ? ', ' : '') . $weeks . ' ' . __n('week', 'weeks', $weeks, true);
				$relativeDate .= $days > 0 ? ($relativeDate ? ', ' : '') . $days . ' ' . __n('day', 'days', $days, true) : '';
			} elseif (abs($days) > 0) {
				// days and hours
				$relativeDate .= ($relativeDate ? ', ' : '') . $days . ' ' . __n('day', 'days', $days, true);
				$relativeDate .= $hours > 0 ? ($relativeDate ? ', ' : '') . $hours . ' ' . __n('hour', 'hours', $hours, true) : '';
			} elseif (abs($hours) > 0) {
				// hours and minutes
				$relativeDate .= ($relativeDate ? ', ' : '') . $hours . ' ' . __n('hour', 'hours', $hours, true);
				$relativeDate .= $minutes > 0 ? ($relativeDate ? ', ' : '') . $minutes . ' ' . __n('minute', 'minutes', $minutes, true) : '';
			} elseif (abs($minutes) > 0) {
				// minutes only
				$relativeDate .= ($relativeDate ? ', ' : '') . $minutes . ' ' . __n('minute', 'minutes', $minutes, true);
			} else {
				// seconds only
				$relativeDate .= ($relativeDate ? ', ' : '') . $seconds . ' ' . __n('second', 'seconds', $seconds, true);
			}

			if (!$backwards) {
				$relativeDate = sprintf(__('%s ago', true), $relativeDate);
			}
		}
		return $relativeDate;
	}
	
	public function gmt($string = null) {
		if ($string != null) {
			$string = $this->fromString($string);
		} else {
			$string = time();
		}
		$string = $this->fromString($string);
		$hour = intval(date("G", $string));
		$minute = intval(date("i", $string));
		$second = intval(date("s", $string));
		$month = intval(date("n", $string));
		$day = intval(date("j", $string));
		$year = intval(date("Y", $string));

		return gmmktime($hour, $minute, $second, $month, $day, $year);
	}
	
	public function format($format, $date = null, $invalid = false) {
		$time = $this->fromString($date);
		$_time = $this->fromString($format);

		if (is_numeric($_time) && $time === false) {
			$format = $date;
			return $this->i18nFormat($_time, $format, $invalid);
		}
		if ($time === false && $invalid !== false) {
			return $invalid;
		}
		return date($format, $time);
	}
	
	public function i18nFormat($date, $format = null, $invalid = false) {
		$date = $this->fromString($date);
		if ($date === false && $invalid !== false) {
			return $invalid;
		}
		if (empty($format)) {
			$format = '%x';
		}
		$format = $this->convertSpecifiers($format, $date);
		return strftime($format, $date);
	}
	
	public function is_today($dateString) {
		$date = $this->fromString($dateString);
		return date('Y-m-d', $date) == date('Y-m-d', time());
	}
	
	public function is_this_week($dateString) {
		$date = $this->fromString($dateString);
		return date('W Y', $date) == date('W Y', time());
	}
	
	public function is_this_month($dateString) {
		$date = $this->fromString($dateString);
		return date('m Y',$date) == date('m Y', time());
	}
	
	public function is_this_year($dateString) {
		$date = $this->fromString($dateString);
		return  date('Y', $date) == date('Y', time());
	}
	
	public function was_yesterday($dateString) {
		$date = $this->fromString($dateString);
		return date('Y-m-d', $date) == date('Y-m-d', strtotime('yesterday'));
	}
	
	public function is_tomorrow($dateString) {
		$date = $this->fromString($dateString);
		return date('Y-m-d', $date) == date('Y-m-d', strtotime('tomorrow'));
	}
	
	public function was_within_last($timeInterval, $dateString, $userOffset = null) {
		$tmp = str_replace(' ', '', $timeInterval);
		if (is_numeric($tmp)) {
			$timeInterval = $tmp . ' ' . __('days', true);
		}

		$date = $this->fromString($dateString, $userOffset);
		$interval = $this->fromString('-'.$timeInterval);

		if ($date >= $interval && $date <= time()) {
			return true;
		}

		return false;
	}
	
	public function is_within_next() {
	}
}