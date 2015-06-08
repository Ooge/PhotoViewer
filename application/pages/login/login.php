<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
    Our Login page.
    We make use of the Page class to display this page
*/
// The stlesheets loaded for this page
$stylesheets = array(base_url('assets/css/login.css'));
// No script for this page
$scripts = array();
// Title of the page
$title = 'Login';
// Output buffer to get the contents of the page and set it into a variable
ob_start();
?>

<div class="login_container">
    <h1 class="login-title">Login</h1>
    <span style="color:red; font-weight:bold;">
        <?php
        // Form validation errors
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
    <div class="input-group">
        <input type="text" name="username" id="username" placeholder="Username">
    </div>
    <div class="input-group">
        <input type="password" name="password" id="password" placeholder="Password">
    </div>
    <div class="input-group">
        <?php echo form_checkbox('remember_me', '1', TRUE); ?>
        <?php echo form_label('Remember me ', 'remember_me'); ?>
    </div>
    <div class="input-group">
        <?php echo form_submit(array('class' => 'btn normal login', 'name' => 'login_submit', 'value' => 'Login')); ?>
    </div>
    <?php echo form_close(); ?>
    <hr>
    <div class="bottom-group">
        Don't have an account? <a href="<?php echo base_url('/register'); ?>">Click here to register</a>
    </div>
</div>

<?php
// We end the output butter and get the contents of the page
$content = ob_get_contents();
// Clean up the buffer for future use
ob_end_clean();
// Return the constructed Page object 
return new Page($stylesheets, $scripts, $title, $content, false);
