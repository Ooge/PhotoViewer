<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
| There area two reserved routes:
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

$route['default_controller'] = 'site';
$route['404_override'] = 'site/fourohfour_override';

$route['login'] = 'site/login';
$route['login_user'] = 'site/login_user';
$route['logout'] = 'site/logout';
$route['register'] = 'site/register';
$route['register_user'] = 'site/register_user';
$route['forgot'] = 'site/forgot';
$route['forgot_password'] = 'site/forgot_password';
$route['recover_password.*'] = 'site/recover_password';
$route['set_new_password'] = 'site/set_new_password';

$route['user.*'] = 'user_v/index';

/* End of file routes.php */
/* Location: ./application/config/routes.php */
