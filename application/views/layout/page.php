<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    if (!$page) {
        exit('false');
    }
    $CI =& get_instance();

    $user = $CI->m_session->get_current_user();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Ooge | <?php echo $page->title(); ?></title>
        <link href="//fonts.googleapis.com/css?family=Raleway:400,300,600,700" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <link href="assets/css/global.css" rel="stylesheet" type="text/css" />
        <?php echo $page->stylesheets(); ?>

        <link rel="icon" type="image/png" href="assets/img/favicon.png">
    </head>
    <body>
        <div class="topbar">
            <div class="c1024" id="nav-wrap">
                <div class="nav-logo">
                    <img src="<?php echo base_url('assets/img/ooge-logo.png'); ?>" alt="Ooge-Logo" />
                </div>
                <div class="nav-left">
                    <ul>
                        <?php
                            if($user){
                                echo '<a class="upload_toggle" href="javascript:void(0);"><li class="important"><i class="fa fa-plus"></i>&nbsp;&nbsp;Upload</li></a>';
                            } else {
                                echo 'Login to upload';
                            }
                        ?>

                    </ul>
                </div>
                <div class="nav-right">
                    <?php
                        if($user){
                            if($user->get_rank() == 'admin'){
                            ?>
                            <a href="<?php echo base_url('/admin'); ?>"><li style="color:#D40000;">Admin</li></a>
                            <?php } ?>
                            <a href="<?php echo base_url('/logout'); ?>"><li><?php echo $user->username; ?></li></a>
                            <?php
                        } else {
                            ?>
                            <ul>
                                <a href="<?php echo base_url('/login'); ?>"><li>Login</li></a>
                                <a href="<?php echo base_url('/register'); ?>"><li class="important">Register</li></a>
                            </ul>
                            <?php
                        }
                    ?>
                </div>
            </div>
        </div>
        <?php
        if($page->has_container()){
            ?>
            <div class="c1024" id="container">
                <?php echo $page->content(); ?>
            </div>
            <?php
        } else {
            echo $page->content();
        }
        ?>


        <div class="modalBack">
        	<div class="modal">
        		<div class="modal-header">
        			<div class="modal-title">
        				<h2>Upload an Image</h2>
        			</div>
        			<div class="modal-close">
        				<i class="fa fa-times"></i>
        			</div>
        		</div>
                <?php echo form_open_multipart('', array('id' => 'image-upload'));?>
        			<div class="modal-body">
        				<p>
        					Uploading an image is easy as 1, 2, 3! Just select your image, enter a title and description for your image, and hit that Upload button!
        				</p>
        				<div class="input-group">
        					<input type="file" name="userfile" id="image-file" accept=".png,.jpg,.gif">
        					<br><label for="image">.png, .jpg and .gif only. 10MB limit.</label>
        				</div>
        				<div class="input-group">
        					<input type="text" name="image-title" id="image-title" placeholder="Image Title">
        				</div>
        				<div class="input-group">
        					<textarea id="image-desc" name="image-desc" placeholder="Image Description"></textarea>
        				</div>

        			</div>
        			<div class="modal-footer">
        				<button class="pull-right btn success" type="submit" id="send-file"><i class="fa fa-upload"></i>&nbsp;Upload</button>
        				<button class="pull-right btn error" id="cancel-file"><i class="fa fa-times"></i>&nbsp;Cancel</button>
        				<span id="error" class="pull-right"></span>
        				<div style="clear:both;"></div>
        			</div>
        		</form>
        		<div style="clear:both;"></div>
        	</div>
        </div>

        <script src="assets/js/vendor/jquery.js" type="text/javascript"></script>
        <script src="assets/js/global.js" type="text/javascript"></script>
        <?php echo $page->scripts(); ?>
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-56329183-1', 'auto');
            ga('send', 'pageview');
        </script>
    </body>
</html>
