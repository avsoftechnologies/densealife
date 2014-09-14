<!--{{theme:partial name='blocks/common' albums={{albums}}}}-->
<?php 
$count = count($friends);
?>
<div class="comman-box clearfix">
    <div class="comman-heading">Friends <?php if($count):?>(<?php echo $count;?>)<?php endif;?></div>
    <?php if($count):?>
        <ul class="followers">
            <?php foreach($friends as $friend):?>
            <li>
                <table border="0">
                    <tr>
                    <td>{{user:profile_pic user_id="<?php echo $friend->id;?>"}}</td>
                        <td class="wid-135" rowspan="2">
                            <span class="name"><?php echo $friend->display_name;?></span>
                        </td>
                        <td>
                            <?php add_friend_button($friend->id, $_user->id);?>
                        </td>
                    </tr>
                </table>
            </li>
            <?php endforeach;?>
        </ul>
    <?php else:?>
     <?php if($_user->id == $this->current_user->id):?>
        <div class="txt-center f-bold fs14 mt50">You have not added anyone into your friend list. Find your friends while clicking on the link below:</div>
        <div class="txt-center txt-deco-under"><a href="/profile/friends/find">Find Friends</a></div>
     <?php else: ?>
        <div class="txt-center f-bold fs14 mt50">No Friends</div>
     <?php endif;?>
    <?php endif;?>
</div>