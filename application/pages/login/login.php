<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$stylesheets = array('assets/css/login.css');
$scripts = array();
$title = 'Login';
ob_start();
?>

<div class="login_container">
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
    <div class="input-group">
        <input type="text" name="username" id="username" placeholder="Username">
    </div>
    <div class="input-group">
        <input type="password" name="password" id="password" placeholder="Password">
    </div>

    <?php echo form_checkbox('remember_me', '1', TRUE); ?>
    <?php echo form_label('Remember me ', 'remember_me'); ?>

    <?php echo form_submit(array('class' => 'button success', 'name' => 'login_submit', 'value' => 'Login')); ?>
    <?php echo form_close(); ?>
</div>

<?php
$content = ob_get_contents();
ob_end_clean();
return new Page($stylesheets, $scripts, $title, $content, false);
