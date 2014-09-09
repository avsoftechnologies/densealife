<div class="comman-box clearfix">
    <ul class="search-box clearfix">
        <li>
        <button onClick="window.location.href='/profile/followers/followings'">Following ({{count}})</button>
        </li>
        <li>
        <button>Friends</button>
        </li>
        <li>
        <button>Message</button>
        </li>
    </ul>
</div>
<div class="comman-box clearfix">
    <div class="comman-heading">{{title}}</div>
    <ul class="followers">
        <?php if($requests):?>
        <?php foreach($requests as $request):?>
        <li> 
            <span>
                {{user:profile_pic user_id='<?php echo $request->user_id;?>'}}
            </span>
            <?php add_friend_button($request->user_id,$_user->id);?>
            <span class="name">
                <?php echo $request->display_name; ?>
            </span> 
<!--            <span class="name">
                Location: <?php echo $follower->address_line1;?>, <?php echo $follower->address_line2;?>
            </span> -->
        </li>
        <?php endforeach;?>
        <?php else:?>
        No Request Pending
        <?php endif; ?>
    </ul>
</div>