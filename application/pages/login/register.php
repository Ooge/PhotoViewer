<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$stylesheets = array('assets/css/login.css');
$scripts = array();
$title = 'Register';
ob_start();
?>
<div class="login_container">

    <span style="color:red; font-weight:bold;"><?php echo validation_errors(); ?></span>
    <?php echo form_open('register_user', array('data-abide' => 1)); ?>
    <div class="input-group">
        <input type="text" name="first_name" value="<?php echo set_value('first_name'); ?>" id="first_name" placeholder="First Name">
    </div>
    <div class="input-group">
        <input type="text" name="last_name" value="<?php echo set_value('last_name'); ?>" id="last_name" placeholder="Last Name">
    </div>
    <div class="input-group">
        <input type="text" name="username" value="<?php echo set_value('username'); ?>" id="username" placeholder="Username">
    </div>
    <div class="input-group">
        <input type="email" name="email" value="<?php echo set_value('email'); ?>" id="email" placeholder="E-Mail">
    </div>
    <div class="input-group">
        <?php echo form_dropdown('day', build_days(), set_value('day', 'err'), 'id="day_dropdown"'); ?>
        <?php echo form_dropdown('month', build_months(), set_value('month', 'err'), 'id="month_dropdown"'); ?>
        <?php echo form_dropdown('year', build_years(), set_value('year', 'err'), 'id="year_dropdown"'); ?>
    </div>
    <div class="input-group">
        <input type="password" name="password" value="" id="password" placeholder="Password">
    </div>
    <div class="input-group">
        <input type="password" name="password_repeat" value="" id="password_repeat" placeholder="Confirm Password">
    </div>
    <div class="input-group">
        <?php echo form_submit(array('class' => 'btn normal login', 'name' => 'register_submit', 'value' => 'Register')); ?>
    </div>
    <?php echo form_close(); ?>
    <hr>
    <div class="bottom-group">
        Already have an account? <a href="/login">Go Login!</a>
    </div>
</div>

<?php
$content = ob_get_contents();
ob_end_clean();
return new Page($stylesheets, $scripts, $title, $content, false);
