<?php 
$rand = rand(0, time());
$notification = $data;
$unserialized = unserialize($notification->data);
$data = $unserialized['data']['comment'];
?>
<span class="img-left">
        {{user:profile_pic user_id='<?php echo $notification->sender_id;?>'}}
</span>
<span class="center-text">
    <span class="fl">
        <strong><?php echo $data['user_name'];?></strong>&nbsp;
        has commented on the <strong><?php echo $data['entry_title'];?></strong>
        <div class="clear mb25"></div>
        <span class="fr">
            <a class="fancybox" href="#approve_<?php echo $rand;?>" title="Please approve it to be visible to others.">Show</a>
        </span>
    </span>
</span>
<span class="time-log">{{generic:time_ago datetime='{{data:created_on}}'}}</span>
<div id="approve_<?php echo $rand;?>" style="width:400px;display: none;">
    <h3>Comment</h3>
        <?php
            if($data['media']!=''):
                $media = unserialize($this->encrypt->decode($data['media']));?>
                    <a class="fancybox-effects-b pl10" data-fancybox-group="button" href="<?php echo $media['data']['comment']['path'];?>">
                        <img src="<?php echo base_url();?>files/medium/<?php echo $media['data']['comment_id'];?>" alt="" width="200" height="200"/>
                    </a> 
        <?php 
            endif;?>
            <span>
                <p><?php echo nl2br($data['comment']); ?></p>
            </span>
    
    <span class="fr">
        <input type="button" value="Approve" class="button" onclick='notification.approve("<?php echo $data['comment_id'];?>")'/>
        <input type="button" value="Decline" class="button" onclick='notification.decline("<?php echo $data['comment_id'];?>")'/>
        <input type="button" value="Block User" class="button" onclick='notification.block("<?php echo $notification->sender_id;?>")'/>
    </span>
</div>