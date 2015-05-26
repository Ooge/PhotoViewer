<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$page = load_page($main_content, $this->_ci_cached_vars);
if (!$page) {
    exit('Page doesnt exist');
}
$pg = array('page' => $page);

$this->load->view('layout/page.php', $pg);

?>