<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Plugin_Generic extends Plugin
{

    public $version     = '1.0.0';
    public $name        = array(
        'en' => 'Generic',
    );
    public $description = array(
        'en' => 'Generic Plugin.',
    );

    public function time_ago()
    {
        $datetime     = $this->attribute('datetime', now());
        $is_timestamp = $this->attribute('convert', true);
        $timestamp    = ($is_timestamp) ? (int)strtotime($datetime) : $datetime;

        //type cast, current time, difference in timestamps
        $current_time = now();
        $diff         = $current_time - $timestamp;
        //echo date_default_timezone_get();
        //intervals in seconds
        $intervals = array(
            'year'   => 31556926, 'month'  => 2629744, 'week'   => 604800, 'day'    => 86400, 'hour'   => 3600, 'minute' => 60
        );

        //now we just find the difference
        if ( $diff == 0 ) {
            return 'just now';
        }

        if ( $diff < 60 ) {
            return $diff == 1 ? $diff . ' second ago' : $diff . ' seconds ago';
        }

        if ( $diff >= 60 && $diff < $intervals['hour'] ) {
            $diff = floor($diff / $intervals['minute']);
            return $diff == 1 ? $diff . ' minute ago' : $diff . ' minutes ago';
        }

        if ( $diff >= $intervals['hour'] && $diff < $intervals['day'] ) {
            $diff = floor($diff / $intervals['hour']);
            return $diff == 1 ? $diff . ' hour ago' : $diff . ' hours ago';
        }

        if ( $diff >= $intervals['day'] && $diff < $intervals['week'] ) {
            $diff = floor($diff / $intervals['day']);
            return $diff == 1 ? $diff . ' day ago' : $diff . ' days ago';
        }

        if ( $diff >= $intervals['week'] && $diff < $intervals['month'] ) {
            $diff = floor($diff / $intervals['week']);
            return $diff == 1 ? $diff . ' week ago' : $diff . ' weeks ago';
        }

        if ( $diff >= $intervals['month'] && $diff < $intervals['year'] ) {
            $diff = floor($diff / $intervals['month']);
            return $diff == 1 ? $diff . ' month ago' : $diff . ' months ago';
        }

        if ( $diff >= $intervals['year'] ) {
            $diff = floor($diff / $intervals['year']);
            return $diff == 1 ? $diff . ' year ago' : $diff . ' years ago';
        }
    }
    
}
