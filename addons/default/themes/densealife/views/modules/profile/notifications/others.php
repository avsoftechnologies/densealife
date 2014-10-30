<ul>
    <?php foreach ( $notifications as $notification ):?>
        <li>
            <?php if($notification->type == Notify::TYPE_STAR):?>
                <?php 
                    $unserialized = unserialize($notification->data);
                ?>
                <span class="img-left">
                        {{user:profile_pic user_id='<?php echo $notification->sender_id;?>'}}
                </span>
                <span class="center-text">
                    <span class="fl">
                        <strong><?php echo $notification->sender_name;?></strong>&nbsp;
                        has stared the post.
                    </span>
                </span>
                <span class="time-log">{{generic:time_ago datetime="<?php echo $notification->created_on;?>"}}</span>
            <?php endif;?>
            <?php if($notification->type == Notify::TYPE_SHARE):?>
                <span class="img-left">
                        {{user:profile_pic user_id='<?php echo $notification->sender_id;?>'}}
                </span>
                <span class="center-text">
                    <span class="fl">
                        <strong><?php echo $notification->sender_name;?></strong>&nbsp;
                        shared the post with you.
                    </span>
                </span>
                <span class="time-log">{{generic:time_ago datetime="<?php echo $notification->created_on;?>"}}</span>
            <?php endif;?>
            <?php if($notification->type == Notify::TYPE_FOLLOW):?>
                <span class="img-left">
                        {{user:profile_pic user_id='<?php echo $notification->sender_id;?>'}}
                </span>
                <span class="center-text">
                    <span class="fl">
                        <strong><?php echo $notification->sender_name;?></strong>&nbsp;
                        started following you.
                    </span>
                </span>
                <span class="time-log">{{generic:time_ago datetime="<?php echo $notification->created_on;?>"}}</span>
            <?php endif;?>
        </li>
    <?php endforeach; ?>
</ul>