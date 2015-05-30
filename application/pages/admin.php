<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$stylesheets = array(base_url('assets/css/admin.css'));
$scripts = array(base_url('assets/js/vendor/highcharts.js'),base_url('assets/js/vendor/highcharts.theme.dark.js'), base_url('assets/js/admin.js'));
$title = 'Admin';
$CI =& get_instance();

$user = $CI->m_session->get_current_user();
ob_start();
?>
<h1>Hi <?php echo $user->username; ?> - Welcome to the Admin Panel</h1>
<div class="admin-section" id="browser_chart">
stats
</div>
<div class="admin-section" id="ip_addresses">
    <h3>Latest IP addresses</h3>
    <ul class="iplist">
    <?php
    foreach($last_ips as $ip) {
        ?>
        <li><?php echo $ip['last_ipv4']; ?></li>
        <?php
    }
    ?>
    </ul>
</div>
<?php
$content = ob_get_contents();
ob_end_clean();
return new Page($stylesheets, $scripts, $title, $content);
