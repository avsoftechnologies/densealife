<?php if (!empty($friends)): ?>
    <div class="comman-box">
        <span class="heading-comman">Friends</span>
        <ul class="friends clearfix">
            <?php foreach ($friends as $friend): ?>
                <li>
                    <a href="/user/<?php echo $friend->username; ?>" title='<?php echo $friend->display_name; ?>'>
                        {{user:profile_pic user_id='<?php echo $friend->user_id; ?>'}}
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif;