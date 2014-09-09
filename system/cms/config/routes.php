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
  |	example.com/class/method/id/
  |
  | In some instances, however, you may want to remap this relationship
  | so that a different class/function is called than the one
  | corresponding to the URL.
  |
  | Please see the user guide for complete details:
  |
  |	http://codeigniter.com/user_guide/general/routing.html
  |
  | -------------------------------------------------------------------------
  | RESERVED ROUTES
  | -------------------------------------------------------------------------
  |
  | There are two reserved routes:
  |
  |	$route['default_controller'] = 'welcome';
  |
  | This route indicates which controller class should be loaded if the
  | URI contains no data. In the above example, the "welcome" class
  | would be loaded.
  |
  |	$route['404_override'] = 'errors/page_missing';
  |
  | This route will tell the Router what URI segments to use if those provided
  | in the URL cannot be matched to a valid route.
  |
 */

$route['default_controller'] = 'pages';
$route['404_override']       = 'pages';

$route['admin/help/([a-zA-Z0-9_-]+)']                     = 'admin/help/$1';
$route['admin/([a-zA-Z0-9_-]+)/(:any)']                   = '$1/admin/$2';
$route['admin/(login|logout|remove_installer_directory)'] = 'admin/$1';
$route['admin/([a-zA-Z0-9_-]+)']                          = '$1/admin/index';

$route['api/ajax/(:any)']             = 'api/ajax/$1';
$route['api/([a-zA-Z0-9_-]+)/(:any)'] = '$1/api/$2';
$route['api/([a-zA-Z0-9_-]+)']        = '$1/api/index';
$route['fb_login']                    = 'facebook_login/login/index';
$route['login']                       = 'users/login';
$route['register']                    = 'users/register';

$route['album/create/(:any)/(:num)'] = 'users/album/create/$1/$2';
$route['album/(:any)']               = 'users/album/$1';

$route['photo/(:any)']       = 'users/photo/index/$1';
$route['photo/upload']       = 'users/photo/upload';
$route['user/(:any)/(:any)'] = 'users/$2/$1';

$route['user/(:any)']                        = 'users/view/$1';
$route['my-profile']                         = 'users/index';
$route['edit-profile']                       = 'users/edit';
$route['densealife-page/create_event']       = 'eventsmanager/create/event';
$route['densealife-page/create_interest']    = 'eventsmanager/create/interest';
$route['densealife-page/i-interests']        = 'profile/index/events/interest';
$route['densealife-page/i-interests/(:any)'] = 'profile/index/events/interest/$1';
$route['densealife-page/i-trending']         = 'profile/index/trending/interest';
$route['densealife-page/i-popular']          = 'profile/index/trending/interest';
$route['densealife-page/i-favorite']         = 'profile/index/favorite/interest';

$route['densealife-page/events/(:any)'] = 'profile/index/events/event/$1';
$route['densealife-page']               = 'profile/index';
$route['densealife-page/notifications'] = 'profile/notifications/index';
$route['densealife-page/(:any)']        = 'profile/index/$1';

$route['sitemap.xml'] = 'sitemap/xml';
