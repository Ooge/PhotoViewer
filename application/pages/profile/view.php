<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$CI =& get_instance();
$profile = $profile_user->get_profile();
$profile_posts = $profile->get_posts();
$profile_rank = $profile_user->get_rank();
$logged_in_user = $CI->m_session->get_current_user();

$stylesheets = array('assets/css/profile.css');
$scripts = array('assets/js/profile.js');
$title = '@' . $profile_user->username;
ob_start();
?>
    <div id="content">
        <div class="row martop20 marbot20">
            <!-- User Panel -->
            <div class="large-3 columns">
                <div id="user-detail-panel">
                    <img id="profile-avatar" src="<?php echo get_gravatar($profile_user->email, $s = 200); ?>" alt="profile-avatar" />
                    <div id="profile-nametag">
                        <?php
                        if($profile_rank == 'admin') {
                            ?>
                            <span class="alert label" style="margin:0;">ADMIN</span>
                            <?php
                        }
                        ?>
                        <h4 style="margin:0;"><?php echo $profile_user->first_name . ' ' . $profile_user->last_name; ?></h4>
                        <h5 class="subheader" style="margin:0;"><?php echo '@' . $profile_user->username; ?></h5>
                    </div>
                    <?php
                    if($logged_in_user){
                        if(!$is_self) {
                            if($logged_in_user->is_following($profile_user->id)){
                                echo '<button id="toggle-follow" name="toggle-follow" class="button fol following" data-profile-user-id="' . $profile_user->id . '"><i class="fa fa-check"></i>&nbsp;Following</button>';
                            } else {
                                echo '<button id="toggle-follow" name="toggle-follow" class="button fol follow" data-profile-user-id="' . $profile_user->id . '"><i class="fa fa-plus"></i>&nbsp;Follow</button>';
                            }
                        }
                    } else {
                        echo '<a id="toggle-follow" name="toggle-follow" class="button fol follow" href="'. base_url('login') .'"><i class="fa fa-plus"></i>&nbsp;Follow</a>';
                    }
                    ?>

                </div>
            </div>
            <!-- Post Panel -->
            <div class="large-6 columns">
            <?php
            if($is_self){
                ?>
                <textarea style="resize:none;height:100px;"id="new_post_message" name="new_post_message" placeholder="New Message..."></textarea>
                <button class="button success" id="new_post_submit" name="new_post_submit">Post</button>
                <?php
            }
            ?>
                <div id="post-container">
                    <?php
                    if(count($profile_posts) == 0) {
                        echo '<em>There are no posts!</em>';
                    }
                    foreach($profile_posts as $post) {
                        ?>
                        <div class="post-cell" data-post-id="<?php echo md5($post->id); ?>">
                            <div class="post-header clearfix">
                                <div class="left"><span class="post-name"><?php echo $profile_user->first_name . ' ' . $profile_user->last_name; ?></span>&nbsp;<span class="post-username"><?php echo '@' . $profile_user->username; ?></span></div>
                                <div class="post-time right"><?php echo time_ago($post->time_posted) . ' ago'; ?></div>
                            </div>
                            
                            <div class="post-body">
                                <?php 
                                $message = $CI->security->xss_clean($post->post_message);
                                $message = convert_link_to_clickable($message);
                                echo $message;
                                ?>
                            </div>
                            <div class="post-toolbar clearfix martop5">
                                <div class="left">
                                <?php
                                if($post->has_faved($logged_in_user)){
                                    echo '<a class="post-toolbar-fav has_faved"><span class="favorites" data-post-id="' . md5($post->id) . '">' . $post->favs . '</span> <i class="fa fa-star"></i></a>';
                                } else {
                                    echo '<a class="post-toolbar-fav"><span class="favorites" data-post-id="' . md5($post->id) . '">' . $post->favs . '</span> <i class="fa fa-star"></i></a>';
                                }
                                ?>
                                </div>
                                <div class="right">
                                <?php if($is_self){ ?>
                                    <a class="post-toolbar-delete"><i class="fa fa-times post-toolbar-delete"></i></a>
                                <?php } ?>
                                </div>
                            </div>
                            <hr class="post-rule">
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <!-- Stats Panel -->
            <div class="large-3 columns">
                <div id="stats-panel">
                    <div class="row">
                        <div class="large-4 columns">
                            <span class="count-text">Posts</span>
                            <span class="post-count"><?php echo $profile_user->post_count(); ?></span>
                        </div>
                        <div class="large-4 columns">
                            <span class="count-text">Flwers</span>
                            <span class="followers-count"><?php echo $profile_user->follower_count(); ?></span>
                        </div>
                        <div class="large-4 columns">
                            <span class="count-text">Flwing</span>
                            <span class="following-count"><?php echo $profile_user->following_count(); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modals -->
        <div id="confirm-post-delete" class="reveal-modal tiny" data-reveal>
            <h3>Confirm</h3>
            <p>Are you sure you want to delete this post?</p>

            <div class="reveal-bottom-buttons">
                <button id="confirm-post-delete-yes" class="button alert" style="margin-right:100px;">Yes</a>
                <button id="confirm-post-delete-no" class="button secondary">No</a>
            </div>
        </div>
    </div>
<?php
$content = ob_get_contents();
ob_end_clean();

return new Page($stylesheets, $scripts, $title, $content);