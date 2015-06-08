<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// This file automatically loads our classes from the classes directory
// By doing this we do not have to do includes on every sinle page when we
// wish to use them, instead we make them all available globally
function classAutoLoader( $className ) {
    if (file_exists(APPPATH . 'classes/' . $className . '.php')) {
        include_once APPPATH . 'classes/' . $className . '.php';
    }
}
spl_autoload_register( 'classAutoLoader' );

function load_page($page_name, $vars = array()) {
    if (file_exists(APPPATH . 'pages/' . $page_name . '.php')) {
        if (count($vars) > 0) {
            foreach ($vars as $var => $val) {
                $$var = $val;
            }
        }
        return require(APPPATH . 'pages/' . $page_name . '.php');
    }
    return false;
}
