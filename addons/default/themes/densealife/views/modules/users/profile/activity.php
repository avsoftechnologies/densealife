<div class="comman-box clearfix">
<div class="comman-heading">Activity</div>
<div class="clear">&nbsp;</div>
<?php if($allowed):?>
<?php echo $this->comments->display_my_comments($_user, true, true); ?>
<?php else:?>

    <div class="block-pages">

        <h2>This Profile is Private</h2>
        <span>to view thier activity, send them a friend request.</span>
        <?php add_friend_button($_user->id,$this->current_user->id);?>

    </div>
<?php endif;?>
</div>