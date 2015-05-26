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
                        <li class="important"><a href="<?php echo base_url('/upload'); ?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;Upload</a></li>
                    </ul>
                </div>
                <div class="nav-right">
                    <?php
                        if($user){
                            if($user->get_rank() == 'admin'){
                            ?>
                            <li><a style="color:#D40000;" href="<?php echo base_url('/admin'); ?>">Admin</a>
                            <?php } ?>
                            <li><a href="<?php echo base_url('/logout'); ?>"><?php echo $user->username; ?></a></li>
                            <?php
                        } else {
                            ?>
                            <ul>
                                <li><a href="<?php echo base_url('/login'); ?>">Login</a></li>
                                <li class="important"><a href="<?php echo base_url('/register'); ?>">Register</a></li>
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

        <script src="assets/js/jquery.js" type="text/javascript"></script>
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
