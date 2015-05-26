<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$stylesheets = array('assets/css/login.css');
$scripts = array();
$title = 'Register';
ob_start();
?>
<div id="login_container">

    <span style="color:red; font-weight:bold;"><?php echo validation_errors(); ?></span>
    <?php echo form_open('register_user', array('data-abide' => 1)); ?>
    <input type="text" name="first_name" value="<?php echo set_value('first_name'); ?>" id="first_name" placeholder="First Name">
    <input type="text" name="last_name" value="<?php echo set_value('last_name'); ?>" id="last_name" placeholder="Last Name">
    <input type="text" name="username" value="<?php echo set_value('username'); ?>" id="username" placeholder="Username">
    <input type="email" name="email" value="<?php echo set_value('email'); ?>" id="email" placeholder="E-Mail">
    <?php echo form_dropdown('day', build_days(), set_value('day', 'err'), 'id="day_dropdown"'); ?>
    <?php echo form_dropdown('month', build_months(), set_value('month', 'err'), 'style="width:100px" id="month_dropdown"'); ?>
    <?php echo form_dropdown('year', build_years(), set_value('year', 'err'), 'id="year_dropdown"'); ?>
    <input type="password" name="password" value="" id="password" placeholder="Password">
    <input type="password" name="password_repeat" value="" id="password_repeat" placeholder="Confirm Password">
    <input type="submit" name="register_submit" value="Create User" class="button success">
    <?php echo form_close(); ?>
    <hr>
    <p><a href="/login">Back to Login</a></p>
</div>

<?php
$content = ob_get_contents();
ob_end_clean();
return new Page($stylesheets, $scripts, $title, $content, false);
