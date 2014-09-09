<div class="comman-heading">Followers</div>
<?php if ($followers): ?>
    <ul class="followers">
        <?php foreach ($followers as $follower): ?>
            <li>
                <div class="fl">
                    <span><img src="" /></span> 
                    <span class="name"><a href='/densealife-page/<?php echo $follower->username; ?>'><?php echo $follower->display_name; ?></a></span> 
                    <br/>
                    <span class="name fl">Location: <?php echo $follower->address_line1; ?><br/> <?php echo $follower->address_line2; ?><br/><?php echo $follower->address_line3; ?></span> 
                </div>
                <?php if ($follower->user_id != ci()->current_user->id && !$follower->is_friend): ?>
                <div>
                    <button class="common right add-friend button_<?php echo $follower->user_id; ?>" data-user = "<?php echo $follower->user_id; ?>"><?php echo $follower->status_label; ?></button>    
                </div>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
<div class="no-reocrds">No Followers</div>
<?php endif; ?>

<script>
    $(document).ready(function() {
        $('.add-friend').click(function() {
            var user = $(this).data('user');
            if ($(this).text() === 'Add Friend') {
                $.post('/friend/invite_friend', {user: user}, function(response) {
                    if (response.status === 'success') {
                        $('.button_' + user).text('Request Sent');
                    }

                }, 'json');
            }

            if ($(this).text() === 'Respond to request') {
                $.post('/friend/respond_friend', {user: user}, function(response) {
                    if (response.status === 'success') {
                        $('.button_' + user).text('Friends');
                    }
                }, 'json');
            }

        });
    });
</script>
    