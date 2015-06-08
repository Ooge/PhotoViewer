<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
    Our Admin page which shows the pie chart of browsers and latest IP addresses
*/
// Stylesheets for the admin page
$stylesheets = array(base_url('assets/css/admin.css'));
// Scripts for highcharts (which renders our piecharts), the theme for highcharts and the admin js file
$scripts = array(base_url('assets/js/vendor/highcharts.js'),base_url('assets/js/vendor/highcharts.theme.dark.js'), base_url('assets/js/admin.js'));
// Page Title
$title = 'Admin';
// Get the codeigniter instance, giving us access to codeigniter functions
$CI =& get_instance();
// Get the currently logged in user
$user = $CI->m_session->get_current_user();
ob_start();
?>
<h1>Hi <?php echo $user->username; ?> - Welcome to the Admin Panel</h1>
<div class="admin-section" id="browser_chart">
Pie Chart <!-- this gets replaced by high charts -->
</div>
<div class="admin-section" id="ip_addresses">
    <span class='section-header'>Latest IP addresses</span>
    <ul class="iplist">
    <?php
    foreach($last_ips as $ip) { // Echo out all the latest IP addresses used to log into the site
        ?>
        <li><?php echo $ip['last_ipv4']; ?></li>
        <?php
    }
    ?>
    </ul>
</div>
<?php
// Get the contents of a page
$content = ob_get_contents();
ob_end_clean();
// Return the constructed page
return new Page($stylesheets, $scripts, $title, $content);
