<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$stylesheets = array(base_url('assets/css/view_image.css'));
$scripts = array(base_url('assets/js/image_view.js'));
$title = 'View Image';
$CI =& get_instance();

$user = $CI->m_session->get_current_user();
$author = $image_data->get_author();
ob_start();
?>
<div class="image-header">
	<h2><?php echo $image_data->title; ?></h2>
	<span class="muted">By <a href="<?php echo base_url('/user/'.$author->username); ?>"><?php echo $author->username; ?></a> - <?php echo time_ago($image_data->time); ?></span>
</div>
<div class="image-container">
	<img class="image-main" src="<?php echo base_url($image_data->file); ?>" />
</div>
<div class="sidebar-container">
	<button class="btn prev"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;Prev</button>
	<button class="btn next">Next&nbsp;&nbsp;<i class="fa fa-chevron-right"></i></button>
	<div class="sidebar-queue">
		<div class="queue-item">
			<div class="item-left">
				<img src="<?php echo base_url('assets/img/placeholder-70x70.png'); ?>" />
			</div>
			<div class="item-right">
				<h5>Queued Image Name Here</h5>
				<span class="muted">By <a href="/ryan">Ryan Thorn</a> - 17 hours ago</span>
				<span class="muted likes">1272 likes</span>
			</div>
		</div>
		<div class="queue-item">
			<div class="item-left">
				<img src="<?php echo base_url('assets/img/placeholder-70x70.png'); ?>" />
			</div>
			<div class="item-right">
				<h5>Queued Image Name Here</h5>
				<span class="muted">By <a href="/ryan">Ryan Thorn</a> - 17 hours ago</span>
				<span class="muted likes">1272 likes</span>
			</div>
		</div>
		<div class="queue-item">
			<div class="item-left">
				<img src="<?php echo base_url('assets/img/placeholder-70x70.png'); ?>" />
			</div>
			<div class="item-right">
				<h5>Queued Image Name Here</h5>
				<span class="muted">By <a href="/ryan">Ryan Thorn</a> - 17 hours ago</span>
				<span class="muted likes">1272 likes</span>
			</div>
		</div>
		<div class="queue-item">
			<div class="item-left">
				<img src="<?php echo base_url('assets/img/placeholder-70x70.png'); ?>" />
			</div>
			<div class="item-right">
				<h5>Queued Image Name Here</h5>
				<span class="muted">By <a href="/ryan">Ryan Thorn</a> - 17 hours ago</span>
				<span class="muted likes">1272 likes</span>
			</div>
		</div>
		<div class="queue-item">
			<div class="item-left">
				<img src="<?php echo base_url('assets/img/placeholder-70x70.png'); ?>" />
			</div>
			<div class="item-right">
				<h5>Queued Image Name Here</h5>
				<span class="muted">By <a href="/ryan">Ryan Thorn</a> - 17 hours ago</span>
				<span class="muted likes">1272 likes</span>
			</div>
		</div>
	</div>
</div>
<div class="image-comments">
	<div class="comments-container">
		<div class="comment-item">
			<div class="comment-left">
				<img src="<?php echo base_url('assets/img/placeholder-70x70.png'); ?>" />
			</div>
			<div class="comment-right">
				<div class="comment-top"><span class="comment-username">Ryan Thorn</span><span class="comment-time-posted"> - 16 minutes ago</span></div>
				<span class="comment-body">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas molestie sed est at pharetra. Suspendisse sollicitudin faucibus commodo. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Duis tempus cursus neque, sit amet bibendum erat ornare nec. Aenean eget odio ligula.

				Maecenas massa sapien, mattis id fermentum vel, mollis vel libero. Nunc venenatis, risus a dapibus vulputate, nibh est sagittis nunc, eget vehicula ex quam vehicula neque. Maecenas mattis porttitor consequat. Proin laoreet aliquam convallis. Phasellus quis velit vitae risus placerat malesuada.</span>
			</div>
		</div>

		<div class="comment-item">
			<div class="comment-left">
				<img src="<?php echo base_url('assets/img/placeholder-70x70.png'); ?>" />
			</div>
			<div class="comment-right">
				<div class="comment-top"><span class="comment-username">Ryan Thorn</span><span class="comment-time-posted"> - 16 minutes ago</span></div>
				<span class="comment-body">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas molestie sed est at pharetra. Suspendisse sollicitudin faucibus commodo. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Duis tempus cursus neque, sit amet bibendum erat ornare nec. Aenean eget odio ligula.

				Maecenas massa sapien, mattis id fermentum vel, mollis vel libero. Nunc venenatis, risus a dapibus vulputate, nibh est sagittis nunc, eget vehicula ex quam vehicula neque. Maecenas mattis porttitor consequat. Proin laoreet aliquam convallis. Phasellus quis velit vitae risus placerat malesuada.</span>
			</div>
		</div>
	</div>
</div>
<div style="clear:both;"></div>
<?php
$content = ob_get_contents();
ob_end_clean();
return new Page($stylesheets, $scripts, $title, $content);
