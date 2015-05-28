<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$stylesheets = array(base_url('assets/css/login.css'));
$scripts = array();
$title = 'Password Changed';
ob_start();
?>
<div id="mid">
    <span id="logo">
        <span id="logo-right">Ooge</span>
        <span id="logo-uk">UK</span>
    </span>
    <h3>Password changed!</h3>
    <h5>You may now login again.</h5>
</div>

<?php
$content = ob_get_contents();
ob_end_clean();
return new Page($stylesheets, $scripts, $title, $content);
