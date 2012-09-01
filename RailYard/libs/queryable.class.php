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

/*
$data = array(1,2,3,4,5,6,7,8,9);
$query = new Queryable($data);
$result = $query->where(function($m) { return $m > 5; });
var_dump($result);
*/

class Queryable implements Iterator {
	
	public $_data = array();
	private $_current = array();
	
	public function __construct($data) {
		$this->_data = $data;
	}
	
	/**
	* Applies an accumulator function over a sequence.
	* @param function $lambda function($total, $current)
	* @return mixed
	* @access public
	*/
	public function aggregate($lambda) {
		$aggregate = null;
		foreach($this->_data as $item) {
			$aggregate = $lambda($aggregate, $item);
		}
		return $aggregate;
	}	
	
	/**
	* Determines whether all the elements of a sequence satisfy a condition.
	* @param function $lambda function($current)
	* @return bool
	* @access public
	*/
	public function all($lambda) {
		foreach($this->_data as $item) {
			$result = $lambda($item);
			if($result == false)
				return false;
		}
		
		return true;
	}
	
	/**
	* Determines whether a sequence contains any elements.
	* @param function $lambda function($current)
	* @return bool
	* @access public
	*/
	public function any($lambda) {
		foreach($this->_data as $item) {
			$result = $lambda($item);
			if($result)
				return true;
		}
		
		return false;
	}
	
	/**
	* Computes the average of a sequence of values.
	* @param function $lambda function($current)
	* @return float
	* @access public
	*/
	public function average($lambda = null) {
		$count = count($this->_data);
		$total = 0.0;
		
		if($count == 0)
			return 0.0;
			
		$lambda = ($lambda == null) ? function($current) { return $current; } : $lambda;
		
		foreach($this->_data as $item) {
			$value = $lambda($item);
			$total += $value;
		}
		
		return $total / $count;
	}
	
	/**
	* Concatenates two sequences.
	* @param mixed $query the query to merge with either Queryable or Array
	* @return Queryable
	* @access public
	*/
	public function concat($query) {
		if(is_array($query))
			$data = array_merge($this->_data, $query);
		else
			$data = array_merge($this->_data, $query->_data);
		
		return new Queryable($data);
	}
	
	/**
	* Determines whether a sequence contains a specified element by using the default equality comparer.
	* @param mixed $element the element to search for
	* @param array $options options for matching the item in collection
	* @return bool
	* @access public
	*/
	public function contains($element, $options = array()) {
		foreach($this->_data as $item) {
			if($item === $element)
				return true;
		}
		
		return false;
	}
	
	/**
	* Returns the number of elements in a sequence.
	* @param function $lambda function($current)
	* @return int
	* @access public
	*/
	public function count($lambda = null) {
		$total = 0;
		
		if($lambda == null) {
			$total = count($this->_data);
		} else {
			foreach($this->_data as $item) {
				$result = $lambda($item);
				if($result) $total++;
			}
		}
		
		return $total;
	}
	
	/**
	* Returns distinct elements from a sequence by using the default equality comparer to compare values.
	* @param int $flag the optional parameter may be used to modify the sorting behavior using these values:
	*	SORT_REGULAR - compare items normally (don't change types)
	*	SORT_NUMERIC - compare items numerically
	*	SORT_STRING - compare items as strings
	*	SORT_LOCALE_STRING - compare items as strings, based on the current locale.
	* @return Queryable
	* @access public
	*/
	public function distinct($flag = null) {
		if($flag == null) {
			return new Queryable(array_unique($this->_data));
		} else {
			return new Queryable(array_unique($this->_data, $flag));	
		}
	}
	
	/**
	* Returns the first element of a sequence.
	* @return mixed
	* @access public
	*/
	public function first() {
		$data = $this->_data;
		return reset($data);
	}
	
	/**
	* Groups the elements of a sequence according to a specified key selector function.
	* @param function $lambda function($current)
	* @return array
	* @access public
	*/
	public function group($lambda) {
		$grouped = array();
		
		$query = $this->order($lambda);
		
		$last = $query->first();
		foreach($query as $item) {
			$key = $lambda($item);
			if($last == $key && array_key_exists($key, $grouped)) {
				$grouped[(string)$key][] = $item;
			} else if($last == $key) {
				$grouped[(string)$key] = array($item);
			} else if($last != $key) {
				$grouped[(string)$key] = array($item);
			}
			
			$last = $key;
		}
		
		return $grouped;
	}
	
	/**
	* Correlates the elements of two sequences based on matching keys.
	* @param Queryable $inner_query description
	* @return Queryable
	* @access public
	*/
	public function join($inner_query, $outer_selector, $inner_selector, $selector) {
		$data = array();
		
		$outer_data = $this->_data;
		$inner_data = (is_array($inner_query)) ? $inner_query : $inner_query->_data;
		
		foreach($outer_data as $outer_item) {
			foreach($inner_data as $inner_item) {
				$inner_key = $outer_selector($outer_item);
				$outer_key = $inner_selector($inner_item);
				
				if($inner_key == $outer_key) {
					$data[] = $data;$selector($outer_item, $inner_item);
				}
			}
		}
		
		return new Queryable($data);
	}
	
	/**
	* Returns the last element in a sequence.
	* @return mixed
	* @access public
	*/
	public function last() {
		$data = $this->_data;
		return end($data);
	}
	
	/**
	* Returns the maximum value
	* @param function $lambda function($current)
	* @return mixed
	* @access public
	*/
	public function max($lambda = null) {
		if($lambda == null) {
			return max($this->_data);
		} else {
			$data = array();
			foreach($this->_data as $item) {
				$data[] = $lambda($item);
			}
			return max($data);
		}
	}
	
	/**
	* Returns the minimum value of a generic
	* @param function $lambda function($current)
	* @return mixed
	* @access public
	*/
	public function min($lambda = null) {
		if($lambda == null) {
			return min($this->_data);
		} else {
			$data = array();
			foreach($this->_data as $item) {
				$data[] = $lambda($item);
			}
			return min($data);
		}
	}
	
	/**
	* Sorts the elements of a sequence in order according to a key.
	* @param function $lambda function($current)
	* @param array $options options for matching the item in collection
	* @return Queryable
	* @access public
	*/
	public function order($lambda, $options = array()) {
		//TODO: add multi pass to the sort function. Will need to pass the sorting lambda on to the new Queryable.
		
		$sorter = function($lhs, $rhs) use ($lambda, $options)  {
			$left = $lambda($lhs);
			$right = $lambda($rhs);
			
			if ($left == $right)
				return 0;
			
			if(isset($options['direction']) && $options['direction'] == 'desc')
				return ($left < $right) ? 1 : -1;
			else
				return ($left < $right) ? -1 : 1;
		};
		
		usort($this->_data, $sorter);
		return new Queryable($this->_data);
	}
	
	/**
	* Projects each element of a sequence into a new form.
	* @param function $lambda function($current)
	* @return Queryable
	* @access public
	*/
	public function select($lambda) {
		$data = array();
		foreach($this->_data as $item) {
			$data[] = $lambda($item);
		}
		
		return new Queryable($data);
	}
	
	/**
	* Returns the only element of a sequence, and throws an exception if there is not exactly one element in the sequence.
	* @param function $lambda function($current)
	* @return mixed
	* @access public
	*/
	public function single($lambda = null) {
		
		if($lambda == null) {
			if(count($this->_data) != 1) 
				throw new UnexpectedValueException();
				
			return $this->first();
		} else {
			$data = array();
			foreach($this->_data as $key => $item) {
				$result = $lambda($item);
				if($result)
					$data[$key] = $item;
			}
			
			if(count($data) != 1) 
				throw new UnexpectedValueException();
				
			return reset($data);
		}
	}
	
	/**
	* Bypasses a specified number of elements in a sequence and then returns the remaining elements.
	* @param int $amount The number of elements to skip
	* @return Queryable
	* @access public
	*/
	public function skip($amount) {
		$data = array_slice($this->_data, $amount);
		return new Queryable($data);
	} 
	
	/**
	* Computes the sum of a sequence of values
	* @param function $lambda function($current)
	* @return float
	* @access public
	*/
	public function sum($lambda = null) {
		$lambda = ($lambda == null) ? function($current) { return $current; } : $lambda;
				
		$total = 0.0;
		foreach($this->_data as $key => $item) {
			$value = $lambda($item);
			$total += $value;
		}
		
		return $total;
	}
	
	/**
	* Returns a specified number of contiguous elements from the start of a sequence.
	* @param int $amount The number of elements to take
	* @return Queryable
	* @access public
	*/
	public function take($amount) {
		$data = array_slice($this->_data, 0, $amount);
		return new Queryable($data);
	}
	
	/**
	* Filters a sequence of values based on a predicate.: Determines whether a sequence contains any elements.
	* @param function $lambda function($current)
	* @return Queryable
	* @access public
	*/
	public function where($lambda) {
		$data = array();
		foreach($this->_data as $item) {
			$result = $lambda($item);
			if($result) 
				$data[] = $item;
		}
		
		return new Queryable($data);
	}
	
	/**
	* Merges two sequences by using the specified predicate function.
	* @param Type $variable description
	* @return Queryable
	* @access public
	*/
	public function zip($query, $lambda) {
		
		$outer_data = $this->_data;
		$inner_data = (is_array($query)) ? $query : $query->_data;
		
		$count = min(count($outer_data), count($inner_data));
		for($i = 0; $i < $count; $i++) {
			$data[] = $lambda($outer_data[$i], $inner_data[$i]);
		}
		
		return new Queryable($data);
	}
	
	/**
	* Iterator implementation
	*/
	function rewind() {
		$current = reset($this->_data);
		$current = each($this->_data);
		$this->_current = $current;
	}

	function current() {
		$value = $this->_current['value'];
		return $value;
	}

	function key() {
		$key = $this->_current['key'];
		return $key;
	}

	function next() {
		$current = each($this->_data);
		$this->_current = $current;
	}

	function valid() {
		$result = !empty($this->_current);
		return $result;
	}
}