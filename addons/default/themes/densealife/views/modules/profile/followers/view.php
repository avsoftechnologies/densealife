
{{ theme:partial name="blocks/links" }}

<div class="comman-box clearfix">
    <div class="comman-heading">{{title}}</div>
    <ul class="followers">
        <?php if($followers):?>
        <?php foreach($followers as $follower):?>
        <li> 
            <span>
                {{user:profile_pic user_id='<?php echo $follower->user_id;?>'}}
            </span>
            <?php add_friend_button($follower->user_id,$_user->id);?>
            <span class="name">
                <?php echo $follower->display_name; ?>
            </span> 
<!--            <span class="name">
                Location: <?php echo $follower->address_line1;?>, <?php echo $follower->address_line2;?>
            </span> -->
        </li>
        <?php endforeach;?>
        <?php else:?>
        No Followers
        <?php endif; ?>
    </ul>
</div>