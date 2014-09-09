<ul>
    <?php foreach($notifications as $notification):?>
        <li>
            {{user:profile_pic user_id='<?php echo $notification['user_id'];?>'}}
            <span class="left-links">
                <?php add_friend_button($notification['user_id'], $this->current_user->id);?>
            </span>
        </li>
    <?php endforeach;?>
</ul>