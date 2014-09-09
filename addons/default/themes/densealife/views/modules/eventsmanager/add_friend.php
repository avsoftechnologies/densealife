{{ theme:partial name='metadata' }}
<style>
    html, ul, li{background-color:#F9F9F9}
    ul{list-style-type: none !important;}
    ul.followers li {
        float: left;
        margin: 2px 1px;
        width: 49%;
    }
</style>
<script>
    $('#add_friend').ajaxForm({
        type: 'POST',
        delegation: true, // for live response
        dataType: 'json',
        beforeSubmit: function() {
            $.fancybox.showLoading();
        },
        success: function(response) {
         if(response.status=='success'){
             selector = $('#friend_li_' + response.response.friend_id);
             width = selector.width();
             height = selector.height();
             selector.html(response.response.msg).css({
                 width:width,
                 height:height,
                 'text-align':'center',
                 'font-weight' : 'bold',
                 'font-size' : '14px'
             });
             setTimeout(function(){
                if($('.my_friends:visible').length == 0 )
                parent.$.fancybox.close();
             }, 3000 ); 
         }   
        },
        error: function() {
        },
        complete: function() {
            $.fancybox.hideLoading();
        }
    });
</script>

<div class="comman-heading">Invite Friends</div>
<?php if($friends) :?>
<ul class="followers">
    <?php 
    foreach ( $friends as $friend ): ?>
        <li style='border:1px solid #efefef; background-color: white;' id="friend_li_<?php echo $friend->user_id; ?>" class="my_friends">
            <form method='post' action='/eventsmanager/add_friend' id='add_friend'>
                <div class="fl wid-70"  style='width:70px;'>
                    <span class='fl'>
                        {{user:profile_pic user_id='<?php echo $friend->user_id; ?>'}}
                    </span>

                    <span class="name fl">
                        <a href='/densealife-page/<?php echo $friend->username; ?>'>
                            <?php echo ucfirst($friend->display_name); ?>
                        </a>           
                    </span>
                    <span>
                        <?php 
                        $data = array(
                            'rec_id' => $friend->user_id,
                            'event' => $event->id
                        );
                        ?>
                        <input type="hidden" name="data" value="<?php echo $this->encrypt->encode(serialize($data));?>"/>
                        <button class="btn-color common" onclick="$('#add_friend').submit(); return false;">Add Friends</button>
                    </span>
                </div>
            </form>
        </li>
    <?php endforeach; ?>
</ul>
<?php else:?>
<?php if($friends_count) :?>
You have already invited all your friends to this event.
<?php else:?>
You don't have any friends. Make friends through invite them. Click on invite button to make new friends. 
<?php endif;?>
<?php endif; ?>
