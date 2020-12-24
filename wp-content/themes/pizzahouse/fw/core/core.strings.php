<?php
/**
 * PizzaHouse Framework: strings manipulations
 *
 * @package	pizzahouse
 * @since	pizzahouse 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Check multibyte functions
if ( ! defined( 'PIZZAHOUSE_MULTIBYTE' ) ) define( 'PIZZAHOUSE_MULTIBYTE', function_exists('mb_strpos') ? 'UTF-8' : false );

if (!function_exists('pizzahouse_strlen')) {
	function pizzahouse_strlen($text) {
		return PIZZAHOUSE_MULTIBYTE ? mb_strlen($text) : strlen($text);
	}
}

if (!function_exists('pizzahouse_strpos')) {
	function pizzahouse_strpos($text, $char, $from=0) {
		return PIZZAHOUSE_MULTIBYTE ? mb_strpos($text, $char, $from) : strpos($text, $char, $from);
	}
}

if (!function_exists('pizzahouse_strrpos')) {
	function pizzahouse_strrpos($text, $char, $from=0) {
		return PIZZAHOUSE_MULTIBYTE ? mb_strrpos($text, $char, $from) : strrpos($text, $char, $from);
	}
}

if (!function_exists('pizzahouse_substr')) {
	function pizzahouse_substr($text, $from, $len=-999999) {
		if ($len==-999999) { 
			if ($from < 0)
				$len = -$from; 
			else
				$len = pizzahouse_strlen($text)-$from;
		}
		return PIZZAHOUSE_MULTIBYTE ? mb_substr($text, $from, $len) : substr($text, $from, $len);
	}
}

if (!function_exists('pizzahouse_strtolower')) {
	function pizzahouse_strtolower($text) {
		return PIZZAHOUSE_MULTIBYTE ? mb_strtolower($text) : strtolower($text);
	}
}

if (!function_exists('pizzahouse_strtoupper')) {
	function pizzahouse_strtoupper($text) {
		return PIZZAHOUSE_MULTIBYTE ? mb_strtoupper($text) : strtoupper($text);
	}
}

if (!function_exists('pizzahouse_strtoproper')) {
	function pizzahouse_strtoproper($text) {
		$rez = ''; $last = ' ';
		for ($i=0; $i<pizzahouse_strlen($text); $i++) {
			$ch = pizzahouse_substr($text, $i, 1);
			$rez .= pizzahouse_strpos(' .,:;?!()[]{}+=', $last)!==false ? pizzahouse_strtoupper($ch) : pizzahouse_strtolower($ch);
			$last = $ch;
		}
		return $rez;
	}
}

if (!function_exists('pizzahouse_strrepeat')) {
	function pizzahouse_strrepeat($str, $n) {
		$rez = '';
		for ($i=0; $i<$n; $i++)
			$rez .= $str;
		return $rez;
	}
}

if (!function_exists('pizzahouse_strshort')) {
	function pizzahouse_strshort($str, $maxlength, $add='...') {
		if ($maxlength < 0) 
			return $str;
		if ($maxlength == 0) 
			return '';
		if ($maxlength >= pizzahouse_strlen($str))
			return strip_tags($str);
		$str = pizzahouse_substr(strip_tags($str), 0, $maxlength - pizzahouse_strlen($add));
		$ch = pizzahouse_substr($str, $maxlength - pizzahouse_strlen($add), 1);
		if ($ch != ' ') {
			for ($i = pizzahouse_strlen($str) - 1; $i > 0; $i--)
				if (pizzahouse_substr($str, $i, 1) == ' ') break;
			$str = trim(pizzahouse_substr($str, 0, $i));
		}
		if (!empty($str) && pizzahouse_strpos(',.:;-', pizzahouse_substr($str, -1))!==false) $str = pizzahouse_substr($str, 0, -1);
		return ($str) . ($add);
	}
}

// Clear string from spaces, line breaks and tags (only around text)
if (!function_exists('pizzahouse_strclear')) {
	function pizzahouse_strclear($text, $tags=array()) {
		if (empty($text)) return $text;
		if (!is_array($tags)) {
			if ($tags != '')
				$tags = explode($tags, ',');
			else
				$tags = array();
		}
		$text = trim(chop($text));
		if (is_array($tags) && count($tags) > 0) {
			foreach ($tags as $tag) {
				$open  = '<'.esc_attr($tag);
				$close = '</'.esc_attr($tag).'>';
				if (pizzahouse_substr($text, 0, pizzahouse_strlen($open))==$open) {
					$pos = pizzahouse_strpos($text, '>');
					if ($pos!==false) $text = pizzahouse_substr($text, $pos+1);
				}
				if (pizzahouse_substr($text, -pizzahouse_strlen($close))==$close) $text = pizzahouse_substr($text, 0, pizzahouse_strlen($text) - pizzahouse_strlen($close));
				$text = trim(chop($text));
			}
		}
		return $text;
	}
}

// Return slug for the any title string
if (!function_exists('pizzahouse_get_slug')) {
	function pizzahouse_get_slug($title) {
		return pizzahouse_strtolower(str_replace(array('\\','/','-',' ','.'), '_', $title));
	}
}

// Replace macros in the string
if (!function_exists('pizzahouse_strmacros')) {
	function pizzahouse_strmacros($str) {
		return str_replace(array("{{", "}}", "((", "))", "||"), array("<i>", "</i>", "<b>", "</b>", "<br>"), $str);
	}
}

// Unserialize string (try replace \n with \r\n)
if (!function_exists('pizzahouse_unserialize')) {
	function pizzahouse_unserialize($str) {
		if ( is_serialized($str) ) {
			try {
				$data = unserialize($str);
			} catch (Exception $e) {
				dcl($e->getMessage());
				$data = false;
			}
			if ($data===false) {
				try {
					$data = unserialize(str_replace("\n", "\r\n", $str));
				} catch (Exception $e) {
					dcl($e->getMessage());
					$data = false;
				}
			}
			return $data;
		} else
			return $str;
	}
}
?>