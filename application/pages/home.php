<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$stylesheets = array(base_url('assets/css/home.css'));
$scripts = array();
$title = 'Home';
$CI =& get_instance();

$user = $CI->m_session->get_current_user();
ob_start();
?>
<h1>Gallery</h1>
<div class="gallery">
	<?php
		if($latestImages != 0){
			foreach($latestImages as $image){
				//echo '<a href="'.base_url($image->gid).'"><div class="gallery-item"><img src="'.$image->get_thumbnail().'" alt="'.$image->title.'" title="'.$image->title.'" /></div></a>';
				var_dump($image->get_thumbnail());
			}
		} else {
			echo 'No images posted. <a class="upload_toggle" href="javascript:void(0);">Be the first</a>';
		}
	?>
</div>
<?php
$content = ob_get_contents();
ob_end_clean();
return new Page($stylesheets, $scripts, $title, $content);
