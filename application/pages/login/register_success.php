<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
    Our Register success page
*/
// Stylesheets
$stylesheets = array(base_url('assets/css/login.css'));
// No scripts
$scripts = array();
// Title of the page
$title = 'Register Success';
// Output buffer collecting page contents
ob_start();
?>
<div id="mid">
    <h3>Great, your account has been created!</h3>
    <h5>You will be redirected shortly.</h5>
</div>

<?php
// Put page contents into variable
$content = ob_get_contents();
ob_end_clean();
// Create new page object and return it
return new Page($stylesheets, $scripts, $title, $content);
