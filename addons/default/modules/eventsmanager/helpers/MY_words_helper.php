<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
* Events Manager date helper
*
* @author       Ankit Vishwakarma <ankitvishwakarma@sify.com>
*/

// ------------------------------------------------------------------------

/**
* Sum up a text with a maximum count of characters
*/
function textSumUp($text, $maxLength)
{
	if(strlen($text) > $maxLength)
	{
		$wrappedText = wordwrap($text, $maxLength, '[cut]', 1);
		$wraps = explode('[cut]', $wrappedText);
		$cutText = $wraps[0] . '...';
		return $cutText;
	}
	else return $text;
}

?>