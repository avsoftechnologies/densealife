<div class="comman-heading">Pending Approval</div>
<span class="seperator">&nbsp;</span>
<div class="fr"> 
    <a href="#pending_approval_all" title="Show All ">Show All </a>
    |  
    <a href="#pending_approval" title="Show Current Events' Pending">Show Current Events' Pending</a></div>
<span class="seperator">&nbsp;</span>
<span class="seperator">&nbsp;</span>

<?php foreach ($posts as $item):?>
    <div class="container <?php echo 'pa_'.$item->id;?>">
        <?php if ($item->user_id == $this->current_user->id or ( isset($item->event_author_id) and $item->event_author_id == $this->current_user->id)): ?>
            <span class="delete_post"><a href="javascript:void(0);" class="post-delete" data-id = '<?php echo $item->id; ?>' title="Delete">[x]</a></span>
        <?php endif; ?>
        <div class="header">
            <div class="profile_pic">
                {{user:profile_pic user_id='<?php echo $item->user_id; ?>' comment_id = '<?php echo $item->id; ?>'}}
            </div>
            <div class="post_title">
                <span class="display_name"><?php echo $item->display_name; ?> </span>
                <?php if ($this->router->fetch_method() != 'wall' && $item->entry_title != ''): ?>
                     added a post on <?= anchor($item->uri, $item->entry_title, array('class' => 'f-bold')) ?> says-
                <?php endif; ?>
                <br />
                <span class="time time-ago">
                    <?php echo time_passed(strtotime($item->priority)); ?>
                </span>
            </div>
        </div>
        <?php if (!empty($item->comment_on_share)): ?>
            <div class="comments">
                <?= $item->comment_on_share ?>
            </div>
            <div class="shared_post">
            <?php endif; ?>
            <div class="comments">
                <?php
                if ($item->media != ''):
                    $media = unserialize($this->encrypt->decode($item->media));
                    ?>
                    <a class="fancybox-effects-b fl" data-fancybox-group="button" href="<?php echo $media['data']['path']; ?>"><img src="<?php echo base_url(); ?>files/medium/<?php echo $media['data']['id']; ?>" alt="" width="200" height="200"/></a> 
                    <?php
                endif;
                ?>
                <span class="clear"></span>
                <span class="fl">
                    <?php if (Settings::get('comment_markdown') and $item->parsed): ?>
                        <?php echo parse_comment($item->parsed); ?>
                    <?php else: ?>
                        <p><?php echo nl2br(parse_comment($item->comment)); ?></p>
                    <?php endif ?>
                </span>
            </div>
                <span class="fr">
            <input type="button" value="Approve" class="button" onclick='notification.approve("<?php echo $item->id;?>")'/>
            <input type="button" value="Decline" class="button" onclick='notification.decline("<?php echo $item->id;?>")'/>
            <input type="button" value="Block User" class="button" onclick='notification.block("<?php echo $item->user_id;?>")'/>
        </span>
            <span class="seperator"></span>
        </div>
        
    </div>
    <span class="seperator">&nbsp;</span>
<?php
endforeach;


