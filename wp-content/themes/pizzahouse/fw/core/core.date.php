<?php
/**
 * PizzaHouse Framework: date and time manipulations
 *
 * @package	pizzahouse
 * @since	pizzahouse 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Convert date from MySQL format (YYYY-mm-dd) to Date (dd.mm.YYYY)
if (!function_exists('pizzahouse_sql_to_date')) {
	function pizzahouse_sql_to_date($str) {
		return (trim($str)=='' || trim($str)=='0000-00-00' ? '' : trim(pizzahouse_substr($str,8,2).'.'.pizzahouse_substr($str,5,2).'.'.pizzahouse_substr($str,0,4).' '.pizzahouse_substr($str,11)));
	}
}

// Convert date from Date format (dd.mm.YYYY) to MySQL format (YYYY-mm-dd)
if (!function_exists('pizzahouse_date_to_sql')) {
	function pizzahouse_date_to_sql($str) {
		if (trim($str)=='') return '';
		$str = strtr(trim($str),'/\-,','....');
		if (trim($str)=='00.00.0000' || trim($str)=='00.00.00') return '';
		$pos = pizzahouse_strpos($str,'.');
		$d=trim(pizzahouse_substr($str,0,$pos));
		$str=pizzahouse_substr($str,$pos+1);
		$pos = pizzahouse_strpos($str,'.');
		$m=trim(pizzahouse_substr($str,0,$pos));
		$y=trim(pizzahouse_substr($str,$pos+1));
		$y=($y<50?$y+2000:($y<1900?$y+1900:$y));
		return ''.($y).'-'.(pizzahouse_strlen($m)<2?'0':'').($m).'-'.(pizzahouse_strlen($d)<2?'0':'').($d);
	}
}

// Return difference or date
if (!function_exists('pizzahouse_get_date_or_difference')) {
	function pizzahouse_get_date_or_difference($dt1, $dt2=null, $max_days=-1, $date_format='') {
		static $gmt_offset = 999;
		if ($gmt_offset==999) $gmt_offset = (int) get_option('gmt_offset');
		if ($max_days < 0) $max_days = pizzahouse_get_theme_option('show_date_after', 30);
		if ($dt2 == null) $dt2 = date('Y-m-d H:i:s');
		$dt2n = strtotime($dt2)+$gmt_offset*3600;
		$dt1n = strtotime($dt1);
		if (is_numeric($dt1n) && is_numeric($dt2n)) {
			$diff = $dt2n - $dt1n;
			$days = floor($diff / (24*3600));
			if (abs($days) < $max_days)
				return sprintf($days >= 0 ? esc_html__('%s ago', 'pizzahouse') : esc_html__('in %s', 'pizzahouse'), pizzahouse_get_date_difference($days >= 0 ? $dt1 : $dt2, $days >= 0 ? $dt2 : $dt1));
			else {
				return pizzahouse_get_date_translations(date(empty($date_format) ? get_option('date_format') : $date_format, $dt1n));
			}
		} else
			return pizzahouse_get_date_translations($dt1);
	}
}

// Difference between two dates
if (!function_exists('pizzahouse_get_date_difference')) {
	function pizzahouse_get_date_difference($dt1, $dt2=null, $short=1, $sec = false) {
		static $gmt_offset = 999;
		if ($gmt_offset==999) $gmt_offset = (int) get_option('gmt_offset');
		if ($dt2 == null) $dt2n = time()+$gmt_offset*3600;
		else $dt2n = strtotime($dt2)+$gmt_offset*3600;
		$dt1n = strtotime($dt1);
		if (is_numeric($dt1n) && is_numeric($dt2n)) {
			$diff = $dt2n - $dt1n;
			$days = floor($diff / (24*3600));
			$months = floor($days / 30);
			$diff -= $days * 24 * 3600;
			$hours = floor($diff / 3600);
			$diff -= $hours * 3600;
			$min = floor($diff / 60);
			$diff -= $min * 60;
			$rez = '';
			if ($months > 0 && $short == 2)
				$rez .= ($rez!='' ? ' ' : '') . sprintf($months > 1 ? esc_html__('%s months', 'pizzahouse') : esc_html__('%s month', 'pizzahouse'), $months);
			if ($days > 0 && ($short < 2 || $rez==''))
				$rez .= ($rez!='' ? ' ' : '') . sprintf($days > 1 ? esc_html__('%s days', 'pizzahouse') : esc_html__('%s day', 'pizzahouse'), $days);
			if ((!$short || $rez=='') && $hours > 0)
				$rez .= ($rez!='' ? ' ' : '') . sprintf($hours > 1 ? esc_html__('%s hours', 'pizzahouse') : esc_html__('%s hour', 'pizzahouse'), $hours);
			if ((!$short || $rez=='') && $min > 0)
				$rez .= ($rez!='' ? ' ' : '') . sprintf($min > 1 ? esc_html__('%s minutes', 'pizzahouse') : esc_html__('%s minute', 'pizzahouse'), $min);
			if ($sec || $rez=='')
				$rez .=  $rez!='' || $sec ? (' ' . sprintf($diff > 1 ? esc_html__('%s seconds', 'pizzahouse') : esc_html__('%s second', 'pizzahouse'), $diff)) : esc_html__('less then minute', 'pizzahouse');
			return $rez;
		} else
			return $dt1;
	}
}

// Prepare month names in date for translation
if (!function_exists('pizzahouse_get_date_translations')) {
	function pizzahouse_get_date_translations($dt) {
		return str_replace(
			array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December',
				  'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'),
			array(
				esc_html__('January', 'pizzahouse'),
				esc_html__('February', 'pizzahouse'),
				esc_html__('March', 'pizzahouse'),
				esc_html__('April', 'pizzahouse'),
				esc_html__('May', 'pizzahouse'),
				esc_html__('June', 'pizzahouse'),
				esc_html__('July', 'pizzahouse'),
				esc_html__('August', 'pizzahouse'),
				esc_html__('September', 'pizzahouse'),
				esc_html__('October', 'pizzahouse'),
				esc_html__('November', 'pizzahouse'),
				esc_html__('December', 'pizzahouse'),
				esc_html__('Jan', 'pizzahouse'),
				esc_html__('Feb', 'pizzahouse'),
				esc_html__('Mar', 'pizzahouse'),
				esc_html__('Apr', 'pizzahouse'),
				esc_html__('May', 'pizzahouse'),
				esc_html__('Jun', 'pizzahouse'),
				esc_html__('Jul', 'pizzahouse'),
				esc_html__('Aug', 'pizzahouse'),
				esc_html__('Sep', 'pizzahouse'),
				esc_html__('Oct', 'pizzahouse'),
				esc_html__('Nov', 'pizzahouse'),
				esc_html__('Dec', 'pizzahouse'),
			),
			$dt);
	}
}
?>