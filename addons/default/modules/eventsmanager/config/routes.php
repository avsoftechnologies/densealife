<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
  | -------------------------------------------------------------------------
  | URI ROUTING
  | -------------------------------------------------------------------------
  | This file lets you re-map URI requests to specific controller functions.
  |
  | Typically there is a one-to-one relationship between a URL string
  | and its corresponding controller class/method. The segments in a
  | URL normally follow this pattern:
  |
  | 	www.your-site.com/class/method/id/
  |
  | In some instances, however, you may want to remap this relationship
  | so that a different class/function is called than the one
  | corresponding to the URL.
  |
  | Please see the user guide for complete details:
  |
  |	http://www.codeigniter.com/user_guide/general/routing.html
 */
// Maintain admin routes
$route['eventsmanager/admin/categories(:any)?']  = 'admin_categories$1';
$route['eventsmanager/admin(:any)?']             = 'admin$1';
////
////// Rewrite the URLs
$route['eventsmanager']                     = 'eventsmanager/index';

$route['eventsmanager/add_friend']          = 'eventsmanager/add_friend';
$route['eventsmanager/create']              = 'eventsmanager/create/event';
$route['eventsmanager/create_interest']     = 'eventsmanager/create/interest';
$route['eventsmanager/save_cp_pos']         = 'eventsmanager/save_cp_pos';
$route['eventsmanager/get_sub_categories']  = 'eventsmanager/get_sub_categories';
$route['eventsmanager/album_images/(:num)'] = 'eventsmanager/ajax_album_images/$1';
$route['eventsmanager/upload_wall_status']  = 'eventsmanager/upload_wall_status';
$route['eventsmanager/request_youtube']     = 'eventsmanager/request_youtube';
$route['eventsmanager/access_youtube']      = 'eventsmanager/access_youtube';
$route['eventsmanager/save_thumb']          = 'eventsmanager/save_thumb';
$route['eventsmanager/edit/(:any)']         = 'eventsmanager/edit/$1';

$route['friend']                      = 'friend/index';
$route['eventsmanager/search/(:any)'] = 'eventsmanager/ajax_search_events/$1';
$route['eventsmanager/(:any)/(:any)'] = 'eventsmanager/$1/$2';
$route['eventsmanager/wall/(:any)']   = 'eventsmanager/wall/$1';
$route['eventsmanager/(:any)']        = 'eventsmanager/wall/$1';

//$route['eventsmanager/(:any)/(:any)'] 	= 'eventsmanager/support/$1/$2';    // actual : evetnsmanager/support/slug/follow
$route['eventsmanager/past'] = 'eventsmanager/past';
