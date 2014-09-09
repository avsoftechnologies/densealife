<?php //p($data); ?>
<span class="img-left">
        {{user:profile_pic user_id='{{data:sender_id}}'}}
</span>
<span class="center-text">
    <span class="fl">
        <strong>{{data:sender}}</strong>&nbsp;
        Commented on the Event you created. Please approve it to be visible to others.
        <div class="clear mb25"></div>
        <span class="fr">
            <a class="fancybox" href="#approve" title="Please approve it to be visible to others.">Show</a>
        </span>
    </span>
</span>
<span class="time-log">{{generic:time_ago datetime='{{data:created_on}}'}}</span>
<?php $unserialized = unserialize($data->data);?>
<div id="approve" style="width:400px;display: none;">
    <h3>Comment</h3>
        <?php
            
            if($unserialized['data']['media']!=''):
                $media = unserialize($this->encrypt->decode($unserialized['data']['media']));?>
                    <a class="fancybox-effects-b pl10" data-fancybox-group="button" href="<?php echo $media['data']['path'];?>">
                        <img src="<?php echo base_url();?>files/medium/<?php echo $media['data']['id'];?>" alt="" width="200" height="200"/>
                    </a> 
        <?php 
            endif;?>
            <span>
                <p><?php echo nl2br($unserialized['data']['comment']); ?></p>
            </span>
    
    <span class="fr">
        <input type="button" value="Approve" class="button" onclick='notification.approve("<?php echo $unserialized['data']['comment_id'];?>")'/>
        <input type="button" value="Decline" class="button" onclick='notification.decline("<?php echo $unserialized['data']['comment_id'];?>")'/>
        <input type="button" value="Block User" class="button" onclick='notification.block("<?php echo $data->sender_id;?>")'/>
    </span>
</div>