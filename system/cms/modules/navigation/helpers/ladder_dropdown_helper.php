<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * PyroCMS Array Helpers
 * 
 *
 * @author      Ankit Vishwakarma <support@avsoftechnologies.in>
 * @copyright   Copyright (c) 2012, AVSofTechnologies.inc
 */


if ( ! function_exists('ladder_dropdown'))
{
	/**
	 * Merge an array or an object into another object
	 *
	 * @param object $object The object to act as host for the merge.
	 * @param object|array $array The object or the array to merge.
	 */
	
    function ladder_dropdown($array,$depth = 0,$links=array()){
     $return =  $links;
     foreach ($array as $item) {
                $return[$item['id']] = repeater("-|-", $depth).$item['title'];
                if (!empty ($item['children']))
                {
                    $return = ladder_dropdown ($item['children'],$depth+1, $return);
                }
            }
            return $return; 
        }

    }