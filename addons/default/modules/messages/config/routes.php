<?php
$route['messages'] = 'messages/index';
$route['messages/index/(:any)'] = 'messages/index/$1';
$route['messages/create'] = 'messages/create';
$route['messages/search'] = 'messages/search';
$route['messages/search2'] = 'messages/search2';
$route['messages/new'] = 'messages/new_message';
$route['messages/unseen'] = 'messages/get_unseen';
$route['messages/(:any)'] = 'messages/index/$1';

