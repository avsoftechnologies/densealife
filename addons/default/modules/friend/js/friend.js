$(document).ready(function() {
    $('.add_frnd').click(function() {
        var user = $(this).data('user');
        if ($(this).text() == 'Invite Friend') {
            $.post('../friend/add_friend', {user: user}, function(response) {
                if (response.status === 'success') {
                    $('.link_' + user).text('Request Sent');
                }

            }, 'json');
        }
    });
    
    $('#invite-friend').submit(function(){
       email = $(this).find('#invite-email').val();
       friend.inviteByEmail(email); 
    });
});

var friend = {
    add: function(user_id) {
        $.post('/friend/add', {user: user_id}, function(response) {
            if (response.status === 'success') {
                $('.btn_add_friend_'+user_id).text('Request sent');
            }else{
                alert(response.msg);
            }

        }, 'json');
    },
    accept: function(user_id) {
        $.post('/friend/respond_friend', {user: user_id}, function(response) {
            if (response.status === 'success') {
                $('.btn_add_friend_'+user_id).text('Friends');
            }

        }, 'json');
    }, 
    checkPendingNotificationsCount: function(){
        var otherCount = 0;
        $.post('/profile/notifications/pending', {}, function(response) {
            $.each(response.notifications, function(key, value){
                if(value.type === 'message'){
                       $('.message-notification-count').removeClass('d-none').text(value.count);
                   }else if(value.type=== 'friend'){
                       $('.friend-notification-count').removeClass('d-none').text(value.count);
                   }else{
                       otherCount+=parseInt(value.count);
                       $('.other-notification-count').removeClass('d-none').text(otherCount);
                   }
            });

        }, 'json');
    }, 
    follow: function(user_id){
        $.post('/profile/followers/create', {user: user_id}, function(response) {
            if (response.status === 'success') {
                $('.btn_follow_'+user_id).text(response.label);
            }

        }, 'json');
    },
    inviteByEmail: function(email)
    {
        $.post('/profile/friends/invite', {email: email}, function(response) {
            if (response.status === 'success') {
                alert(response.msg);
            }

        }, 'json');
    },
    unfriend : function(user_id){
        $.post('/friend/unfriend', {user: user_id}, function(response) {
            if (response.status === 'success') {
                window.location.realod();
            }

        }, 'json');
    },
    
    invite: function(eid,fid)
    {
        $.post('/profile/friends/invite_event', {entry_id: eid, friend_id:fid}, function(response) {
            if (response.status === 'success') {
                $('.li_'+ fid).fadeOut().remove();
                if($('.friend_suggestions').siblings('li').length === 0){
                    $('.activity-feeds').remove();
                }
                
            }

        }, 'json');
    }, 
    
    cancel_request: function(fid)
    {
        $.post('/friend/cancel_request', {fid: fid}, function(response) {
            if (response.status === 'success') {
                window.location.reload();
            }
        }, 'json');
    }
    
    
};

    friend.checkPendingNotificationsCount();
    setInterval(function(){friend.checkPendingNotificationsCount();},10000);