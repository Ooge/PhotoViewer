<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$stylesheets = array(base_url('assets/css/profile.css'));
$scripts = array(base_url('assets/js/profile.js'));
$title = $profileUser->username . '\'s Profile';

$CI =& get_instance();
$profileImages = $profile->get_images(20);
$user = $this->m_session->get_current_user();

ob_start();
?>
<div>
    <h1><?php echo $profileUser->username . '\'s Gallery'; ?></h1>
</div>
<div class="gallery">
	<?php
		if($profileImages != 0){
			foreach($profileImages as $image){
				echo '<a href="'.base_url($image->gid).'"><div class="gallery-item"><img src="'.$image->file.'" alt="'.$image->title.'" title="'.$image->title.'" /></div></a>';
			}
		} else {
			echo 'This user has not posted any images.';
		}
	?>
</div>
<?php
$content = ob_get_contents();
ob_end_clean();
return new Page($stylesheets, $scripts, $title, $content);
