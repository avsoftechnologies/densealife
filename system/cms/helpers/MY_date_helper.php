<?php defined('BASEPATH') OR exit('No direct script access allowed.');

/**
 * PyroCMS Date Helpers
 * 
 * This overrides Codeigniter's helpers/date_helper.php
 *
 * @author      PyroCMS Dev Team
 * @copyright   Copyright (c) 2012, PyroCMS LLC
 * @package		PyroCMS\Core\Helpers
 */


if (!function_exists('format_date'))
{

	/**
	 * Formats a timestamp into a human date format.
	 *
	 * @param int $unix The UNIX timestamp
	 * @param string $format The date format to use.
	 * @return string The formatted date.
	 */
	function format_date($unix, $format = '')
	{
		if ($unix == '' || !is_numeric($unix))
		{
			$unix = strtotime($unix);
		}

		if (!$format)
		{
			$format = Settings::get('date_format');
		}

		return strstr($format, '%') !== false ? ucfirst(utf8_encode(strftime($format, $unix))) : date($format, $unix);
	}

}

function time_passed($timestamp)
{
   $period    =   '';
     $secsago   =   time() - $timestamp;
 
     if ($secsago < 60){
         $period = $secsago == 1 ? '1 second'     : $secsago . ' seconds';
     }
     else if ($secsago < 3600) {
         $period    =   round($secsago/60);
         $period    =   $period == 1 ? '1 minute' : $period . ' minutes';
     }
     else if ($secsago < 86400) {
         $period    =   round($secsago/3600);
         $period    =   $period == 1 ? '1 hour'   : $period . ' hours';
     }
     else if ($secsago < 604800) {
         $period    =   round($secsago/86400);
         $period    =   $period == 1 ? '1 day'    : $period . ' days';
     }
     else if ($secsago < 2419200) {
         $period    =   round($secsago/604800);
         $period    =   $period == 1 ? '1 week'   : $period . ' weeks';
     }
     else if ($secsago < 29030400) {
         $period    =   round($secsago/2419200);
         $period    =   $period == 1 ? '1 month'   : $period . ' months';
     }
     else {
         $period    =   round($secsago/29030400);
         $period    =   $period == 1 ? '1 year'    : $period . ' years';
     }
     return $period;
}