<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
    User profile page
*/
$stylesheets = array(base_url('assets/css/profile.css'));
$scripts = array(base_url('assets/js/profile.js'));
$title = $profileUser->username . '\'s Profile';

$CI =& get_instance();
// Get the profiles last 20 latest images
$profileImages = $profile->get_images(20);
// Get the logged in user
$user = $CI->m_session->get_current_user();

ob_start();
?>
<div>
    <h1><?php echo $profileUser->username . '\'s Gallery'; ?></h1>
</div>
<div class="gallery">
	<?php
		if($profileImages != 0){
			foreach($profileImages as $image){ // Load the gallery images
				echo '<a href="'.base_url($image->gid).'"><div class="gallery-item"><img src="'.$image->file_location.'" alt="'.$image->title.'" title="'.$image->title.'" /></div></a>';
			}
		} else {
			echo 'This user has not posted any images.';
		}
	?>
</div>
<?php
$content = ob_get_contents();
ob_end_clean();
// Load the constructed page
return new Page($stylesheets, $scripts, $title, $content);
