<div class="msg-drop" id='message-window'>
    <?php foreach ( $conv as $conversation ): ?>
        <?php if ( $this->current_user->id == $conversation->rec_id ): ?>
            <div class='fl' style='width:100%;'>
                <div class='fl'>{{user:profile_pic user_id="<?php echo $conversation->sender_id; ?>"}}</div>
                <div class="arrow_box-left fl mt0 ml20"><?php echo $conversation->message; ?></div>
                <div class='fr'><?php echo time_passed(strtotime($conversation->created_at)); ?></div>
            </div>
            <div class='clear'></div>
        <?php endif; ?>
        <?php if ( $this->current_user->id == $conversation->sender_id ): ?>
            <div class='fr' style='width:100%;'>
                <div class='fl'>{{generic:time_ago datetime='<?php echo $conversation->created_at;?>'}}</div>
                <div class='fr'>{{user:profile_pic user_id="<?php echo $conversation->sender_id; ?>"}}</div>
                <div class="arrow_box-right fr mt0 mr20"><?php echo $conversation->message; ?></div>
            </div>
            <div class='clear'></div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>