<div class="comman-box clearfix">
    <div class="comman-heading">Messages</div>
    <div class="message-header clearfix"> 
        <?php if(isset($action) && $action != 'new_message') :?>
        <span class="right">
            <p class="float-left">{{rec:display_name}}</p>
            <!--<button>Actionq</button>-->
            <button onClick="window.location.href = '/messages/new'">+ New Message</button>
        </span>
        <?php endif;?>
    </div>
    <div class="all-frineds-messages">
        <ul>
            <?php foreach ( $senders as $sender ):  ?>
                {{user:profile user_id="<?php echo $sender->sender_id; ?>"}}
                <li title="{{display_name}}" class="social-buttons">
                    {{user:profile_pic user_id=user_id}}
                <span class="left-links">
                    <a href="{{username}}">{{display_name}}</a>
                </span>
                </li>
                {{/user:profile}}
            <?php endforeach; ?>
        </ul>
    </div>
    <form method="post" action='/messages/create' id='frm-create-message'>
    <div class="main-message-aera">
        <?php echo $board;?>
        <div class="message-reply-block clearfix">
           
            <div class="status-box">
                    <input type='hidden' name='rec_id' value='<?php echo $rec_id;?>'/>
                    <input type='hidden' name='sender_id'  value='<?php echo $this->current_user->id;?>'/>
                    <textarea cols="2" rows="2" name="message" id="status"></textarea>
                    <div class="status-bg"><button class="btn-color post-btn">Reply</button></div>
            </div>
        </div>
    </div>
        </form>
</div>