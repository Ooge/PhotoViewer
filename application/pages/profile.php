<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$stylesheets = array('assets/css/profile.css');
$scripts = array('assets/js/profile.js');
$title = $user->username . '\'s Profile';

$CI =& get_instance();
$author = $image_data->get_author();
$profileImages = $profile->get_images(20);

ob_start();
?>
<div>
    <?php echo $profileUser->username . '\'s Gallery'; ?>
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
