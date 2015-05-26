<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$config['protocol'] = 'smtp';
$config['mailpath'] = '/usr/sbin/sendmail';
$config['charset'] = 'utf-8';
$config['wordwrap'] = TRUE;

$config['smtp_host'] = 'ssl://mail.gandi.net';
$config['smtp_user'] = 'noreply@ryanthorn.uk';
$config['smtp_pass'] = '89e5876d8ce99e346692a710795df714';
$config['smtp_port'] = '465';
$config['smtp_timeout'] = 5;

$config['mailtype'] = 'html';
