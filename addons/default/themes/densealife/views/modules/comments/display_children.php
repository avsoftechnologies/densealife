<?php if ($comments): ?>
    <?php foreach ($comments as $item): ?>
        <li class="comment_children mb10 li_child li-<?php echo $item->id;?>">
            <?php if($item->user_id == $this->current_user->id):?>
            <span class="delete_post">
                <a href="javascript:void(0);" class="post-delete" data-id = '<?php echo $item->id;?>' title="Delete">[x]</a>
            </span>
            <?php endif;?>
            <div class="header">
                <div class="profile_pic">
                    {{user:profile_pic user_id='<?php echo $item->user_id; ?>' dim='32' comment_id = '<?php echo $item->id;?>'}}
                </div>
                <div>
                    <span class="display_name"><?php echo $item->display_name; ?></span>
                    &nbsp;
                    <?php echo nl2br($item->comment) ?>  
                    <br/>
                    <span class="time time-ago">
                        <?php echo time_passed(strtotime($item->created_on));?>
                    </span>
                </div>
            </div>
        </li>
    <?php endforeach ?>
    <?php

 endif ;
 