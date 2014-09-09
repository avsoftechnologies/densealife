<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
* Events Manager date helper
*
* @author       Ankit Vishwakarma <ankitvishwakarma@sify.com>
*/

// ------------------------------------------------------------------------

function time_range($start, $end)
{
	$range = range($start, $end);
	foreach($range as &$time) $time = sprintf("%02d", $time);
	return $range;
}

function sortEventsByPeriod($events)
{
	$sorted_events = array();
	$now = new Datetime();
	
	foreach($events as $event)
	{
		$start_date = new Datetime($event->start_date);
		$end_date = new Datetime($event->end_date);
		if($start_date < $now && $end_date >= $now) { // If event is currently
			$sorted_events['current'][] = $event;
		}
		elseif($start_date->format('Y-m-d') == $now->format('Y-m-d')) {
			$sorted_events['today'][] = $event;
		}
		elseif($start_date->format('Y-W') == $now->format('Y-W')) {
			$sorted_events['week'][] = $event;
		}
		elseif($start_date->format('Y-m') == $now->format('Y-m')) {
			$sorted_events['month'][] = $event;
		}
		elseif($start_date->format('Y-m') > $now->format('Y-m') && $start_date->format('Y') == $now->format('Y')) {
			$sorted_events['month-'.$start_date->format('m')][] = $event;
		}
		elseif($start_date->format('Y') > $now->format('Y')) {
			$sorted_events['year-'.$start_date->format('Y')][] = $event;
		}
	}
	return $sorted_events;
}

function date_fr_to_us($root_date)
{
	if($root_date != '')
	{
		$pieces = explode(' ', $root_date);
		$date = explode('/', $pieces[0]);
		$time = $pieces[1];
		$us_format = '%s-%s-%s %s';
		return sprintf($us_format, $date[2], $date[1], $date[0], $time);
	}
	else return null;
}

/*
 * Matches each symbol of PHP date format standard
 * with Javascript equivalent codeword
 */


function php2js_date_format($php_format)
{
	$PHP_matching_JS = array(
			// Day
			'd' => 'dd',
			'D' => 'D',
			'j' => 'd',
			'l' => 'DD',
			'N' => '',
			'S' => '',
			'w' => '',
			'z' => 'o',
			// Week
			'W' => '',
			// Month
			'F' => 'MM',
			'm' => 'mm',
			'M' => 'M',
			'n' => 'm',
			't' => '',
			// Year
			'L' => '',
			'o' => '',
			'Y' => 'yy',
			'y' => 'y',
			// Time
			'a' => '',
			'A' => '',
			'B' => '',
			'g' => '',
			'G' => '',
			'h' => '',
			'H' => '',
			'i' => '',
			's' => '',
			'u' => ''
	);

	$js_format = "";
	$escaping = false;

	for($i = 0; $i < strlen($php_format); $i++)
	{
		$char = $php_format[$i];
		if($char === '\\') // PHP date format escaping character
		{
			$i++;
			if($escaping) $js_format .= $php_format[$i];
			else $js_format .= '\'' . $php_format[$i];
			$escaping = true;
		}
		else
		{
			if($escaping) { $js_format .= "'"; $escaping = false; }
			if(isset($PHP_matching_JS[$char]))
				$js_format .= $PHP_matching_JS[$char];
			else
			{
				$js_format .= $char;
			}
		}
	}

	return $js_format;
}

// Very important under PHP 5.3 !!
function DEFINE_date_create_from_format()
{

  function date_create_from_format( $dformat, $dvalue )
  {

    $schedule = $dvalue;
    $schedule_format = str_replace(array('Y','m','d', 'H', 'i','a'),array('%Y','%m','%d', '%I', '%M', '%p' ) ,$dformat);
    // %Y, %m and %d correspond to date()'s Y m and d.
    // %I corresponds to H, %M to i and %p to a
    $ugly = strptime($schedule, $schedule_format);
    $ymd = sprintf(
        // This is a format string that takes six total decimal
        // arguments, then left-pads them with zeros to either
        // 4 or 2 characters, as needed
        '%04d-%02d-%02d %02d:%02d:%02d',
        $ugly['tm_year'] + 1900,  // This will be "111", so we need to add 1900.
        $ugly['tm_mon'] + 1,      // This will be the month minus one, so we add one.
        $ugly['tm_mday'], 
        $ugly['tm_hour'], 
        $ugly['tm_min'], 
        $ugly['tm_sec']
    );
    $new_schedule = new DateTime($ymd);

   return $new_schedule;

  }
}

if(!function_exists('date_create_from_format')) { DEFINE_date_create_from_format(); }

?>