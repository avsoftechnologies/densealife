$(document).ready(function(){
   
//top Header right Icons div  1)Friend Request, 2)Account, 3)Notification, 4)Messages
    var socialBlock = '<div class="over-block"><span class="top-arrow"></span></div>';
    var messageData = '<ul><li><img src="images/profile-pictures1.jpg" alt="User Name"><span class="left-links"><a href="">User Name</a></span></li><li><img src="images/profile-pictures1.jpg" alt="User Name"><span class="left-links"><a href="">User Name</a></span></li><li><img src="images/profile-pictures1.jpg" alt="User Name"><span class="left-links"><a href="">User Name</a></span></li><li><img src="images/profile-pictures1.jpg" alt="User Name"><span class="left-links"><a href="">User Name</a></span></li><li><img src="images/profile-pictures1.jpg" alt="User Name"><span class="left-links"><a href="">User Name</a></span></li></ul>'
    var friendsRequest = '<ul><li><img src="images/profile-pictures1.jpg" alt="User Name"><span class="left-links"><button class="btn-color common">Add Friend</button></span></li><li><img src="images/profile-pictures1.jpg" alt="User Name"><span class="left-links"><button class="btn-color common">Add Friend</button></span></li><li><img src="images/profile-pictures1.jpg" alt="User Name"><span class="left-links"><button class="btn-color common">Add Friend</button></span></li><li><img src="images/profile-pictures1.jpg" alt="User Name"><span class="left-links"><button class="btn-color common">Add Friend</button></span></li><li><img src="images/profile-pictures1.jpg" alt="User Name"><span class="left-links"><button class="btn-color common">Add Friend</button></span></li></ul>'
    var accountData = 'dummy'

    //Close POpupBlock
    $('.share').click(function() {
        $(this).parent().parent().last().remove();
    });

    // 1)Friend Request
    $('.friend-request-block').click(function() {
        if ($('div.over-block').length > 0) {
            $('header .top-scrip ul li').find('div').remove();
        }
        $('.friend-notification-count').addClass('d-none');
        $.post('/profile/notifications/pending_requests',{type:'friend'},  function(response){
                $('.friend-request-block').parent('li').append(socialBlock);
            if(response === 'false'){
                $('header .top-scrip ul li div').append('No Friend Notification Pending');
            }else{
                $('header .top-scrip ul li div').append(response);
                
            }
        });
        
    });
    // 2)Account
    $('.my-account-block').click(function() {
        $('.my-account-li').removeClass('d-none');
    });
    // 3)Notification
    $('.notification-block').click(function() {
       $('.other-notification-count').addClass('d-none');
        $.get('/profile/notifications/unseen_other', function(response){
            $('.notification-block').parent('li').append(socialBlock);
            if(response === 'false'){
                $('header .top-scrip ul li div').append('You have no message unseen');
            }else{
                $('header .top-scrip ul li div').append(response);
                
            }
        });
    });
    // 4)Messages
    $('.message-block').click(function() {
       
        $('.message-notification-count').addClass('d-none');
        $.get('/profile/notifications/unseen_message', function(response){
            $('.message-block').parent('li').append(socialBlock);
            if(response === 'false'){
                $('header .top-scrip ul li div').append('You have no message unseen');
            }else{
                $('header .top-scrip ul li div').append(response);
                
            }
        });
    }); 
});

var notification = {
    approve : function(comment_id) {
        $.get('/comments/approve', {cid: comment_id, action:'approve'}, function(response){
            if(response.status=='success'){
                $('.pa_'+ comment_id).slideUp(); 
                $.fancybox.close();
            }
        },'json');
    },
    
    decline : function(comment_id) {
         $.get('/comments/approve', {cid: comment_id, action:'decline'}, function(response){
            if(response.status=='success'){
                $('.pa_'+ comment_id).slideUp(); 
                $.fancybox.close();
            }
        },'json');
    },
    
    block : function(user_id) {
         $.get('/comments/block', {uid:user_id}, function(response){
            if(response.success!=''){
                $('.pa_'+ comment_id).slideUp(); 
                $.fancybox.close();
            }
        },'json');
    },
    auto_approve : function(){
        //this is commented because in case of only one follower if selected i want to deselect it then it would be a probls. 
//        if($('input[name="followers[]"]:checked').length == 0 ){
//            alert('Please select one from the list to approve');
//            return false; 
//        }
    },
    checkPendingNotificationsCount: function(){
        var otherCount = 0;
        $.post('/profile/notifications/pending_count', {}, function(response) {
            $.each(response.notifications, function(key, value){
                if(value.type == 'message'){
                    $('.message-notification-count').removeClass('d-none').text(value.count);
                }else if(value.type == 'friend'){
                    $('.friend-notification-count').removeClass('d-none').text(value.count);
                }else {
                    otherCount+=parseInt(value.count);
                    $('.other-notification-count').removeClass('d-none').text(otherCount);
                }
            });

        }, 'json');
    }
};

notification.checkPendingNotificationsCount();
setInterval(function(){notification.checkPendingNotificationsCount();},10000);