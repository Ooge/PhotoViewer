<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$stylesheets = array(base_url('assets/css/login.css'));
$scripts = array();
$title = 'Register Success';
ob_start();
?>
<div id="mid">
    <span id="logo">
        <span id="logo-right">Ooge</span>
        <span id="logo-uk">UK</span>
    </span>
    <h3>Great, your account has been created!</h3>
    <h5>You will be redirected shortly.</h5>
</div>

<?php
$content = ob_get_contents();
ob_end_clean();
return new Page($stylesheets, $scripts, $title, $content);
