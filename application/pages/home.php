<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$stylesheets = array('assets/css/home.css');
$scripts = array();
$title = 'Home';
$CI =& get_instance();

$user = $CI->m_session->get_current_user();
ob_start();
?>
<h1>Gallery</h1>
<div class="gallery">
	<?php
		foreach($latestImages as $image){
			echo '<img class="gallery-item" src="'.$image->file.'" alt="'.$image->title.'" title="'.$image->title.'" />';
		}
	?>
</div>
<?php
$content = ob_get_contents();
ob_end_clean();
return new Page($stylesheets, $scripts, $title, $content);
