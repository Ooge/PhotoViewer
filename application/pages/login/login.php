<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$stylesheets = array('assets/css/login/login.main.css');
$scripts = array();
$title = 'Login';
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
    if(isset($_GET['code'])){
        switch($_GET['code']) {
            case 1:
                echo '<p>Username or password was incorrect!</p>';
                break;
            default:
                echo '<p>Unknown Error</p>';
                break;
        }
    }
    ?>
</span>

<?php echo form_open('login_user'); ?>
<div class="row">
    <div class="large-12 columns">
        <div class="row collapse">
            <div class="small-2 columns">
                <span class="prefix">@</span>
            </div>
            <div class="small-10 columns">
                <input type="text" name="username" id="username" placeholder="Username">
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="large-12 columns">
        <div class="row collapse">
            <div class="small-2 columns">
                <span class="prefix"><i class="fa fa-lock"></i></span>
            </div>
            <div class="small-10 columns">
                <input type="password" name="password" id="password" placeholder="Password">
            </div>
        </div>
    </div>
</div>


<?php echo form_checkbox('remember_me', '1', TRUE); ?>
<?php echo form_label('Remember me ', 'remember_me'); ?>

<?php echo form_submit(array('class' => 'button success', 'name' => 'login_submit', 'value' => 'Login')); ?>
<?php echo form_close(); ?>
<hr style="margin-top:0">
<p><a href="/register">Create New Account</a>&nbsp;|&nbsp;<a href="/forgot">Forgot password?</a></p>
</div>

<?php
$content = ob_get_contents();
ob_end_clean();
return new Page($stylesheets, $scripts, $title, $content);

