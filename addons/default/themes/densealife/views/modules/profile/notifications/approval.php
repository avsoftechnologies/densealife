<div class="comman-heading">{{title}}</div>
<ul class="followers">
    <span style='color:gray;'>
    Note: You will not get the notifications from all the auto approved followers when they post on the events you created. 
    </span>
    <?php if ($followers): ?>
        <form method="post" action="" id="form-auto-approve">
            <?php foreach ($followers as $follower): ?>
                <li>
                    <table>
                        <tr>
                            <td>
                                <input type="checkbox" <?php echo ($follower['status'] == 'on') ? 'checked="checked"' : ''; ?>  name="followers[]" value="<?php echo $follower['id']; ?>"/>
                            </td>
                            <td>
                                {{user:profile_pic user_id='<?php echo $follower['id']; ?>'}}
                            </td>
                            <td>
                            <span class="name"><?php echo $follower['display_name']; ?></span>
                            </td>
                        </tr>
                    </table>
                </li>
            <?php endforeach; ?>
                <div class="clear"></div>
                <div class="fr"><input type="submit" name="submit" value="Auto Approve" onclick='return notification.auto_approve();'/></div>
        </form>
    <?php else: ?>
        No Followers
    <?php endif; ?>
</ul>