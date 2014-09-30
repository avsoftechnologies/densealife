<div class="container">
    <?php if($item->user_id == $this->current_user->id):?>
        <span class="delete_post"><a href="javascript:void(0);" class="post-delete" data-id = '<?php echo $item->id;?>' title="Delete">[x]</a></span>
    <?php endif;?>
    <div class="header">
        <div class="profile_pic">
            {{user:profile_pic user_id='<?php echo $item->user_id; ?>' comment_id = '<?php echo $item->id;?>'}}
        </div>
        <div class="post_title">
            <span class="display_name"><?php echo $item->display_name; ?> </span>
            <?php if($this->router->fetch_method()!='wall' && $item->entry_title!=''):?>
            &nbsp; &gtdot; <?=anchor($item->uri, $item->entry_title, array('class' => 'f-bold'))?>
            <?php endif;?>
<!--            <span class="color-blue"> &nbsp; shared  <?php echo $item->display_name; ?>'s status</span>-->
            <br />
            <span class="time time-ago">
                <?php echo time_passed(strtotime($item->priority)); ?>
            </span>
        </div>
    </div>
    <?php if(!empty($item->comment_on_share)):?>
        <div class="comments">
            <?=$item->comment_on_share?>
        </div>
        <div class="shared_post">
    <?php endif;?>
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
        <span class='clear'>&nbsp;</span>
        <span class='comman-star stars'>
            <?php echo  $this->comments->link_star($item->id);?>
        </span> 
        <span>
            <a href="/comments/share/<?php echo $item->id; ?>" class="fancybox fancybox.ajax">Share</a>
        </span>
    </div>
    <?php if(!empty($item->comment_on_share)):?>
        </div>
    <?php endif;?>
    <div class="comment-box">
        <ul class="post_comments">
            <?php echo $this->comments->display_my_children($item->id, false); ?>
            <?php $count_post_comments = $this->comments->count_post_comments($item->id);?>
            <?php if($count_post_comments > Comments::LIMIT_POST_COMMENTS):?>
            <li class='f-bold txt-center mb10'>
                <a href="javascript:void(0);" title="View More" class="color-blue view_more_comments" data-id="<?php echo $item->id;?>" data-offset="0">
                    View More (<?php echo ($count_post_comments - Comments::LIMIT_POST_COMMENTS);?>)
                </a>
            </li>
            <?php endif;?>
            <?php if(!$blacklisted and ($allowcomment or $item->is_friend_post)) :?>
            <li>
            <span>{{user:profile_pic user_id='<?php echo $this->current_user->id; ?>' dim='32' comment_id = '<?php echo $item->id;?>'}}</span> 
            <div class="status-aera children">
                <?php echo $this->comments->form($item->id); ?>
            </div>
            </li>
            <?php endif;?>
        </ul>
    </div>
</div>
<span class="seperator">&nbsp;</span>
