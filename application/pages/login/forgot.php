<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
    Our forgot password page
*/
$stylesheets = array(base_url('assets/css/login.css'));
$scripts = array();
$title = 'Forgot Password';
ob_start();
?>
<div id="mid">
<span style="color:red; font-weight:bold;">
    <?php
    echo validation_errors();
    ?>
</span>
<h4>Forgot Password</h4>
<p>Please enter your email address associated with your account</p>
<?php echo form_open('forgot_password'); ?>
<?php echo (isset($message) ? $message : ''); ?>
<input type="text" name="email" id="email" placeholder="Email">
<?php echo form_submit(array('class' => 'button success', 'name' => 'forgot_submit', 'value' => 'Send')); ?>
<?php echo form_close(); ?>
<hr style="margin-top:0">
<p><a href="/login">Back</a></p>
</div>

<?php
$content = ob_get_contents();
ob_end_clean();
return new Page($stylesheets, $scripts, $title, $content);
