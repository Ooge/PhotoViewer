<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$stylesheets = array('assets/css/login/login.main.css');
$scripts = array();
$title = 'Register';
ob_start();
?>
<div id="mid">
<span id="logo">
    <span id="logo-right">Ooge</span>
    <span id="logo-uk">UK</span>
</span>

<span style="color:red; font-weight:bold;"><?php echo validation_errors(); ?></span>
<?php echo form_open('register_user', array('data-abide' => 1)); ?>
    
    <div class="row">
        <div class="large-6 columns">
            <label>
                <input type="text" name="first_name" value="<?php echo set_value('first_name'); ?>" id="first_name" placeholder="First Name">
            </label>
            <small class="error">Invalid first name.</small>
        </div>
        <div class="large-6 columns">
            <label>
                <input type="text" name="last_name" value="<?php echo set_value('last_name'); ?>" id="last_name" placeholder="Last Name">
            </label>
            <small class="error">Invalid last name.</small>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <label>
                <input type="text" name="username" value="<?php echo set_value('username'); ?>" id="username" placeholder="Username">
            </label>
            <small class="error">Invalid username.</small>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <label>
                <input type="email" name="email" value="<?php echo set_value('email'); ?>" id="email" placeholder="E-Mail">
            </label>
            <small class="error">Invalid E-Mail.</small>
        </div>
    </div>

    <div class="row">
        <div class="large-4 columns">
            <label>
                <?php echo form_dropdown('day', build_days(), set_value('day', 'err'), 'id="day_dropdown"'); ?>
            </label>
            <small class="error">Invalid day</small>
        </div>
        <div class="large-4 columns">
            <label>
                <?php echo form_dropdown('month', build_months(), set_value('month', 'err'), 'style="width:100px" id="month_dropdown"'); ?>
            </label>
            <small class="error">Invalid month</small>
        </div>
        <div class="large-4 columns">
            <label>
                <?php echo form_dropdown('year', build_years(), set_value('year', 'err'), 'id="year_dropdown"'); ?>
            </label>
            <small class="error">Invalid year</small>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <label>
                <input type="password" name="password" value="" id="password" placeholder="Password">
            </label>
            <small class="error">Password is required.</small>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <label>
                <input type="password" name="password_repeat" value="" id="password_repeat" placeholder="Confirm Password">
            </label>
            <small class="error">You must confirm your password.</small>
        </div>
    </div>

    <input type="submit" name="register_submit" value="Create User" class="button success">
<?php echo form_close(); ?>
<hr style="margin-top:0">
<p><a href="/login">Back to Login</a></p>
</div>

<?php
$content = ob_get_contents();
ob_end_clean();
return new Page($stylesheets, $scripts, $title, $content);

