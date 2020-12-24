<?php
/**
 * PizzaHouse Framework: theme variables storage
 *
 * @package	pizzahouse
 * @since	pizzahouse 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Get theme variable
if (!function_exists('pizzahouse_storage_get')) {
	function pizzahouse_storage_get($var_name, $default='') {
		global $PIZZAHOUSE_STORAGE;
		return isset($PIZZAHOUSE_STORAGE[$var_name]) ? $PIZZAHOUSE_STORAGE[$var_name] : $default;
	}
}

// Set theme variable
if (!function_exists('pizzahouse_storage_set')) {
	function pizzahouse_storage_set($var_name, $value) {
		global $PIZZAHOUSE_STORAGE;
		$PIZZAHOUSE_STORAGE[$var_name] = $value;
	}
}

// Check if theme variable is empty
if (!function_exists('pizzahouse_storage_empty')) {
	function pizzahouse_storage_empty($var_name, $key='', $key2='') {
		global $PIZZAHOUSE_STORAGE;
		if (!empty($key) && !empty($key2))
			return empty($PIZZAHOUSE_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return empty($PIZZAHOUSE_STORAGE[$var_name][$key]);
		else
			return empty($PIZZAHOUSE_STORAGE[$var_name]);
	}
}

// Check if theme variable is set
if (!function_exists('pizzahouse_storage_isset')) {
	function pizzahouse_storage_isset($var_name, $key='', $key2='') {
		global $PIZZAHOUSE_STORAGE;
		if (!empty($key) && !empty($key2))
			return isset($PIZZAHOUSE_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return isset($PIZZAHOUSE_STORAGE[$var_name][$key]);
		else
			return isset($PIZZAHOUSE_STORAGE[$var_name]);
	}
}

// Inc/Dec theme variable with specified value
if (!function_exists('pizzahouse_storage_inc')) {
	function pizzahouse_storage_inc($var_name, $value=1) {
		global $PIZZAHOUSE_STORAGE;
		if (empty($PIZZAHOUSE_STORAGE[$var_name])) $PIZZAHOUSE_STORAGE[$var_name] = 0;
		$PIZZAHOUSE_STORAGE[$var_name] += $value;
	}
}

// Concatenate theme variable with specified value
if (!function_exists('pizzahouse_storage_concat')) {
	function pizzahouse_storage_concat($var_name, $value) {
		global $PIZZAHOUSE_STORAGE;
		if (empty($PIZZAHOUSE_STORAGE[$var_name])) $PIZZAHOUSE_STORAGE[$var_name] = '';
		$PIZZAHOUSE_STORAGE[$var_name] .= $value;
	}
}

// Get array (one or two dim) element
if (!function_exists('pizzahouse_storage_get_array')) {
	function pizzahouse_storage_get_array($var_name, $key, $key2='', $default='') {
		global $PIZZAHOUSE_STORAGE;
		if (empty($key2))
			return !empty($var_name) && !empty($key) && isset($PIZZAHOUSE_STORAGE[$var_name][$key]) ? $PIZZAHOUSE_STORAGE[$var_name][$key] : $default;
		else
			return !empty($var_name) && !empty($key) && isset($PIZZAHOUSE_STORAGE[$var_name][$key][$key2]) ? $PIZZAHOUSE_STORAGE[$var_name][$key][$key2] : $default;
	}
}

// Set array element
if (!function_exists('pizzahouse_storage_set_array')) {
	function pizzahouse_storage_set_array($var_name, $key, $value) {
		global $PIZZAHOUSE_STORAGE;
		if (!isset($PIZZAHOUSE_STORAGE[$var_name])) $PIZZAHOUSE_STORAGE[$var_name] = array();
		if ($key==='')
			$PIZZAHOUSE_STORAGE[$var_name][] = $value;
		else
			$PIZZAHOUSE_STORAGE[$var_name][$key] = $value;
	}
}

// Set two-dim array element
if (!function_exists('pizzahouse_storage_set_array2')) {
	function pizzahouse_storage_set_array2($var_name, $key, $key2, $value) {
		global $PIZZAHOUSE_STORAGE;
		if (!isset($PIZZAHOUSE_STORAGE[$var_name])) $PIZZAHOUSE_STORAGE[$var_name] = array();
		if (!isset($PIZZAHOUSE_STORAGE[$var_name][$key])) $PIZZAHOUSE_STORAGE[$var_name][$key] = array();
		if ($key2==='')
			$PIZZAHOUSE_STORAGE[$var_name][$key][] = $value;
		else
			$PIZZAHOUSE_STORAGE[$var_name][$key][$key2] = $value;
	}
}

// Add array element after the key
if (!function_exists('pizzahouse_storage_set_array_after')) {
	function pizzahouse_storage_set_array_after($var_name, $after, $key, $value='') {
		global $PIZZAHOUSE_STORAGE;
		if (!isset($PIZZAHOUSE_STORAGE[$var_name])) $PIZZAHOUSE_STORAGE[$var_name] = array();
		if (is_array($key))
			pizzahouse_array_insert_after($PIZZAHOUSE_STORAGE[$var_name], $after, $key);
		else
			pizzahouse_array_insert_after($PIZZAHOUSE_STORAGE[$var_name], $after, array($key=>$value));
	}
}

// Add array element before the key
if (!function_exists('pizzahouse_storage_set_array_before')) {
	function pizzahouse_storage_set_array_before($var_name, $before, $key, $value='') {
		global $PIZZAHOUSE_STORAGE;
		if (!isset($PIZZAHOUSE_STORAGE[$var_name])) $PIZZAHOUSE_STORAGE[$var_name] = array();
		if (is_array($key))
			pizzahouse_array_insert_before($PIZZAHOUSE_STORAGE[$var_name], $before, $key);
		else
			pizzahouse_array_insert_before($PIZZAHOUSE_STORAGE[$var_name], $before, array($key=>$value));
	}
}

// Push element into array
if (!function_exists('pizzahouse_storage_push_array')) {
	function pizzahouse_storage_push_array($var_name, $key, $value) {
		global $PIZZAHOUSE_STORAGE;
		if (!isset($PIZZAHOUSE_STORAGE[$var_name])) $PIZZAHOUSE_STORAGE[$var_name] = array();
		if ($key==='')
			array_push($PIZZAHOUSE_STORAGE[$var_name], $value);
		else {
			if (!isset($PIZZAHOUSE_STORAGE[$var_name][$key])) $PIZZAHOUSE_STORAGE[$var_name][$key] = array();
			array_push($PIZZAHOUSE_STORAGE[$var_name][$key], $value);
		}
	}
}

// Pop element from array
if (!function_exists('pizzahouse_storage_pop_array')) {
	function pizzahouse_storage_pop_array($var_name, $key='', $defa='') {
		global $PIZZAHOUSE_STORAGE;
		$rez = $defa;
		if ($key==='') {
			if (isset($PIZZAHOUSE_STORAGE[$var_name]) && is_array($PIZZAHOUSE_STORAGE[$var_name]) && count($PIZZAHOUSE_STORAGE[$var_name]) > 0)
				$rez = array_pop($PIZZAHOUSE_STORAGE[$var_name]);
		} else {
			if (isset($PIZZAHOUSE_STORAGE[$var_name][$key]) && is_array($PIZZAHOUSE_STORAGE[$var_name][$key]) && count($PIZZAHOUSE_STORAGE[$var_name][$key]) > 0)
				$rez = array_pop($PIZZAHOUSE_STORAGE[$var_name][$key]);
		}
		return $rez;
	}
}

// Inc/Dec array element with specified value
if (!function_exists('pizzahouse_storage_inc_array')) {
	function pizzahouse_storage_inc_array($var_name, $key, $value=1) {
		global $PIZZAHOUSE_STORAGE;
		if (!isset($PIZZAHOUSE_STORAGE[$var_name])) $PIZZAHOUSE_STORAGE[$var_name] = array();
		if (empty($PIZZAHOUSE_STORAGE[$var_name][$key])) $PIZZAHOUSE_STORAGE[$var_name][$key] = 0;
		$PIZZAHOUSE_STORAGE[$var_name][$key] += $value;
	}
}

// Concatenate array element with specified value
if (!function_exists('pizzahouse_storage_concat_array')) {
	function pizzahouse_storage_concat_array($var_name, $key, $value) {
		global $PIZZAHOUSE_STORAGE;
		if (!isset($PIZZAHOUSE_STORAGE[$var_name])) $PIZZAHOUSE_STORAGE[$var_name] = array();
		if (empty($PIZZAHOUSE_STORAGE[$var_name][$key])) $PIZZAHOUSE_STORAGE[$var_name][$key] = '';
		$PIZZAHOUSE_STORAGE[$var_name][$key] .= $value;
	}
}

// Call object's method
if (!function_exists('pizzahouse_storage_call_obj_method')) {
	function pizzahouse_storage_call_obj_method($var_name, $method, $param=null) {
		global $PIZZAHOUSE_STORAGE;
		if ($param===null)
			return !empty($var_name) && !empty($method) && isset($PIZZAHOUSE_STORAGE[$var_name]) ? $PIZZAHOUSE_STORAGE[$var_name]->$method(): '';
		else
			return !empty($var_name) && !empty($method) && isset($PIZZAHOUSE_STORAGE[$var_name]) ? $PIZZAHOUSE_STORAGE[$var_name]->$method($param): '';
	}
}

// Get object's property
if (!function_exists('pizzahouse_storage_get_obj_property')) {
	function pizzahouse_storage_get_obj_property($var_name, $prop, $default='') {
		global $PIZZAHOUSE_STORAGE;
		return !empty($var_name) && !empty($prop) && isset($PIZZAHOUSE_STORAGE[$var_name]->$prop) ? $PIZZAHOUSE_STORAGE[$var_name]->$prop : $default;
	}
}
?>