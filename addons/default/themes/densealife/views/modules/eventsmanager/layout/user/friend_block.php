<span class="heading-comman">Friends</span>
<a href="#">(<?php echo $count;?>)</a>
<ul class="modify-block clearfix">
    <?php foreach($friends as $friend):?>
        <li>
            <span>
                {{url:anchor segments="/user/<?php echo $friend->username;?>" title='<?php echo $friend->first_name;?>'}}
            </span>
            {{user:profile_pic user_id='<?php echo $friend->user_id;?>' w='95' h='95'}}
        </li>
    <?php endforeach;?>
</ul>
