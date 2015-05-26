<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$stylesheets = array('assets/css/home.css');
$scripts = array();
$title = 'Home';
ob_start();
?>
<h1>Gallery</h1>
<div class="gallery">
	<img class="gallery-item" src="assets/img/placeholder-236x236.png" alt="placeholder-image" title="Placeholder Image" />
	<img class="gallery-item" src="assets/img/placeholder-236x236.png" alt="placeholder-image" title="Placeholder Image" />
	<img class="gallery-item" src="assets/img/placeholder-236x236.png" alt="placeholder-image" title="Placeholder Image" />
	<img class="gallery-item" src="assets/img/placeholder-236x236.png" alt="placeholder-image" title="Placeholder Image" />
	<img class="gallery-item" src="assets/img/placeholder-236x236.png" alt="placeholder-image" title="Placeholder Image" />
	<img class="gallery-item" src="assets/img/placeholder-236x236.png" alt="placeholder-image" title="Placeholder Image" />
	<img class="gallery-item" src="assets/img/placeholder-236x236.png" alt="placeholder-image" title="Placeholder Image" />
	<img class="gallery-item" src="assets/img/placeholder-236x236.png" alt="placeholder-image" title="Placeholder Image" />
	<img class="gallery-item" src="assets/img/placeholder-236x236.png" alt="placeholder-image" title="Placeholder Image" />
	<img class="gallery-item" src="assets/img/placeholder-236x236.png" alt="placeholder-image" title="Placeholder Image" />
	<img class="gallery-item" src="assets/img/placeholder-236x236.png" alt="placeholder-image" title="Placeholder Image" />
	<img class="gallery-item" src="assets/img/placeholder-236x236.png" alt="placeholder-image" title="Placeholder Image" />
</div>
<?php
$content = ob_get_contents();
ob_end_clean();
return new Page($stylesheets, $scripts, $title, $content);