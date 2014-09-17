<?php if (!empty($follower_friends)): ?>
    <div class="comman-box clearfix clear">
        <span class="heading-comman">Friends Following</span>
        <ul class="friends-follow">
            <?php foreach ($follower_friends as $follower): ?>
                <li>
                    <a href="" title="<?php echo $follower->name; ?>">
                        {{user:profile_pic user_id='<?php echo $follower->user_id; ?>'}}
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif;
