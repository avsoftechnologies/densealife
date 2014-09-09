<?php

function add_follow_button($user_id, $current_user_id)
{
    if ( $current_user_id != ci()->current_user->id ) {
        $current_user_id = ci()->current_user->id;
    }
    ci()->load->model('profile/follower_m');
    $follower = ci()->follower_m->is_follower($user_id, $current_user_id);

    if ($follower === false):?>
        <button class="btn_follow_<?php echo $user_id;?> btn-color common" onclick="friend.follow('<?php echo $user_id;?>');">Follow</button>
    <?php
    else:?>
        <button class="btn-color common">Following</button>
    <?php 
    endif;
}
