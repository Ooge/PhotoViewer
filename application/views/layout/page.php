<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    /*
     * This is the default structure of a page. Here it includes the header of the page,
     * the content section and the footer of the page
     *
     * We get the content of the page from the currently loaded Page class.
     */

    if (!$page) {
        exit('false');
    }
    $CI =& get_instance();

    $user = $CI->m_session->get_current_user();
?>
<!DOCTYPE html>
<html>
    <head>
        <!-- The title from the Page class -->
        <title>Ooge | <?php echo $page->title(); ?></title>
        <link href="//fonts.googleapis.com/css?family=Raleway:400,300,600,700" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <link href="<?php echo base_url('assets/css/global.css'); ?>" rel="stylesheet" type="text/css" />
        <!-- The stylesheets from the Page class -->
        <?php echo $page->stylesheets(); ?>

        <link rel="icon" type="image/png" href="<?php echo base_url('assets/img/favicon.png'); ?>">
    </head>
    <body>
        <div class="topbar">
            <div class="c1024" id="nav-wrap">
                <div class="nav-logo">
                    <a href="<?php echo base_url(); ?>"><img src="<?php echo base_url('assets/img/ooge-logo.png'); ?>" alt="Ooge-Logo" /></a>
                </div>
                <div class="nav-left">
                    <ul>
                        <?php
                            if($user){ // if the user is logged in, let them click the upload button
                                echo '<a class="upload_toggle" href="javascript:void(0);"><li class="important"><i class="fa fa-plus"></i>&nbsp;&nbsp;Upload</li></a>';
                            } else {
                                echo '<a href="'.base_url('/login').'"><li>Login to upload</li></a>';
                            }
                        ?>

                    </ul>
                </div>
                <div class="nav-right">
                    <?php
                        if($user){ // the user is an admin, show them the admin button
                            if($user->get_rank() == 'admin'){
                            ?>
                            <a href="<?php echo base_url('/admin'); ?>"><li style="color:#D40000;">Admin</li></a>
                            <?php } // if they are not an admin, just show the user name and thats it?>
                            <a href="<?php echo base_url('/logout'); ?>"><li><?php echo $user->username; ?></li></a>
                            <?php
                        } else { // if they are not logged in, show the login and register buttons
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
        if($page->has_container()){ // Check if the page is using the default container or not
            ?>
            <div class="c1024" id="container">
                <?php echo $page->content();  // Content from the page class?>
            </div>
            <?php
        } else {
            // Content from the page class
            echo $page->content();
        }
        ?>

        <!-- Here is the pop over modal for our image upload form -->
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
                <?php echo form_open_multipart('', array('id' => 'image-upload'));  // Open a form for image uploading?>
        			<div class="modal-body">
        				<p>
        					Uploading an image is easy as 1, 2, 3! Just select your image, enter a title and description for your image, and hit that Upload button!
        				</p>
                        <!-- the image input form sections -->
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

        <script src="<?php echo base_url('assets/js/vendor/jquery.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo base_url('assets/js/vendor/jquery.cookie.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo base_url('assets/js/global.js'); ?>" type="text/javascript"></script>
        <!-- We load the page scripts from the Page class -->
        <?php echo $page->scripts(); ?>
        <!-- Our Google analytics tracker -->
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
