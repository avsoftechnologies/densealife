<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$autoload['libraries'] = array('eventsmanager/Events_Validation','eventsmanager/Events_Lib');

$autoload['helper'] = array('html','MY_words','MY_date','MY_crop','form');

$autoload['model'] = array('event_categories_m','event_followers_m','eventsmanager_m','users/profile_m','users/user_m','friend/friend_m');
