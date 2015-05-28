<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$stylesheets = array(base_url('assets/css/login.css'));
$scripts = array();
$title = 'Forgot Password';
ob_start();
?>
<div id="mid">
    <span id="logo">
        <span id="logo-right">Ooge</span>
        <span id="logo-uk">UK</span>
    </span>

    <span style="color:red; font-weight:bold;">
        <?php
        echo validation_errors();
        ?>
    </span>
    <p>Enter a new password</p>
    <?php echo form_open('set_new_password'); ?>

    <div class="row">
        <div class="large-12 columns">
            <label>
                <input type="password" name="password" value="" id="password" placeholder="Password" required>
            </label>
            <small class="error">Password is required.</small>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <label>
                <input type="password" name="password_repeat" value="" id="password_repeat" placeholder="Confirm Password" required>
            </label>
            <small class="error">You must confirm your password.</small>
        </div>
    </div>

    <?php
        echo form_hidden('code', $code);
        echo form_submit(array('class' => 'button success', 'name' => 'forgot_submit', 'value' => 'Send'));
        echo form_close();
    ?>
</div>

<?php
$content = ob_get_contents();
ob_end_clean();
return new Page($stylesheets, $scripts, $title, $content);
