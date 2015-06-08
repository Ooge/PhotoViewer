<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
	Our Home page
*/
// Our stylesheets
$stylesheets = array(base_url('assets/css/home.css'));
$scripts = array();
// Title of the page
$title = 'Home';
// CodeIgniter Instance
$CI =& get_instance();
// Logged in user
$user = $CI->m_session->get_current_user();
ob_start();
?>
<h1>Gallery</h1>
<div class="gallery">
	<?php
		if($latestImages != 0){
			foreach($latestImages as $image){ // Loop through the 20 latest images and display them
				echo '<a href="'.base_url($image->gid).'"><div class="gallery-item"><img src="'.$image->thumb_location.'" alt="'.$image->title.'" title="'.$image->title.'" /></div></a>';
			}
		} else {
			// if there are no images, display text
			echo 'No images posted. <a class="upload_toggle" href="javascript:void(0);">Be the first</a>';
		}
	?>
</div>
<?php

// Get the contents of the page
$content = ob_get_contents();
ob_end_clean();
// Return the constructed page
return new Page($stylesheets, $scripts, $title, $content);
