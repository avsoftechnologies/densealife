$(document).ready(function() {
    //Signup-Function-Popup

//    $("button#submit-btn").click(function() {
//        loading(); // loading
//        setTimeout(function() { // then show popup, deley in .5 second
//            loadPopup(); // function show popup 
//        }, 500); // .5 second
//        return false;
//    });

    /* event for close the popup */
    $("div.close").hover(
            function() {
                $('span.ecs_tooltip').show();
            },
            function() {
                $('span.ecs_tooltip').hide();
            }
    );

    $("div.close").click(function() {
        disablePopup();  // function close pop up
    });

    $(this).keyup(function(event) {
        if (event.which == 27) { // 27 is 'Ecs' in the keyboard
            disablePopup();  // function close pop up
        }
    });

    $("div#backgroundPopup").click(function() {
        disablePopup();  // function close pop up
    });

    $('a.livebox').click(function() {
        alert('Hello World!');
        return false;
    });


    /************** start: functions. **************/
    function loading() {
        $("div.loader").show();
    }
    function closeloading() {
        $("div.loader").fadeOut('normal');
    }

    var popupStatus = 0; // set value

    function loadPopup() {
        if (popupStatus == 0) { // if value is 0, show popup
            closeloading(); // fadeout loading
            $("#signup-form").fadeIn(0500); // fadein popup div
            $("#backgroundPopup").css("opacity", "0.7"); // css opacity, supports IE7, IE8
            $("#backgroundPopup").fadeIn(0001);
            popupStatus = 1; // and set value to 1
        }
    }

    function disablePopup() {
        if (popupStatus == 1) { // if value is 1, close popup
            $("#signup-form").fadeOut("normal");
            $("#backgroundPopup").fadeOut("normal");
            popupStatus = 0;  // and set value to 0
        }
    }
    //Signup-Function-Popup End

    //Hover-image-links
    $('body').on('mouseover','ul.stream li span.image, ul.modify-block li', function(){
        $(this).find('.display-none').show().addClass('hover-aera');
    });
    
    $('body').on('mouseleave','ul.stream li span.image, ul.modify-block li', function(){
        $(this).find('.display-none').hide().removeClass('hover-aera');
    });
    
    $(document).mouseup(function(e)
    {
        var container = $(".over-block");

        if (!container.is(e.target) // if the target of the click isn't the container...
                && container.has(e.target).length === 0) // ... nor a descendant of the container
        {
            container.hide();
        }
    });
    //tabs hide-show
    $('.tab-one').click(function() {
        $('#trending').show();
        $('#favorites').hide();
        $('#upcoming').hide();
        $('.tab-two').removeClass('facebook');
        $('.tab-three').removeClass('facebook');
        if ($(this).hasClass('facebook')) {
            $(this).removeClass('facebook');
        }
        else {
            $(this).addClass('facebook');
        }
    });
    $('.tab-two').click(function() {
        $('#trending').hide();
        $('#favorites').show();
        $('#upcoming').hide();
        $('.tab-one').removeClass('facebook');
        $('.tab-three').removeClass('facebook');
        if ($(this).hasClass('facebook')) {
            $(this).removeClass('facebook');
        }
        else {
            $(this).addClass('facebook');
        }

    });
    $('.tab-three').click(function() {
        $('#trending').hide();
        $('#favorites').hide();
        $('#upcoming').show();
        $('.tab-one').removeClass('facebook');
        $('.tab-two').removeClass('facebook');
        if ($(this).hasClass('facebook')) {
            $(this).removeClass('facebook');
        }
        else {
            $(this).addClass('facebook');
        }
    });


    // Share Facebook, Google Plus, Twitter
    /*$('.share').click(function(){
     $('body').append('<div class="all-share clearfix"><div class="share-block"><div class="social-icons facebook">Facebook</div><div class="social-icons google-plus">Google Plus</div><div class="social-icons twitter">Twitter</div><div class="social-icons densea-life">Densea Life</div><div class="social-icons instagram">Instagram</div><span class="share-close">x</span></div><div class="sharePopup"></div></div>');
     $('.share-close').last().click (function () { 
     $(this).parent().parent().last().remove();    
     });
     });*/
    var shareBlock = '<div class="all-share clearfix"><span class="arrow-up"></span></div>';
    var sharData = '<ul class="social-icons clearfix"><li class="facebookC edged"><a href="#" target="_blank" title="" data-placement="bottom" rel="tooltip" data-original-title="Facebook">Facebook</a></li><li class="twitterC edged"><a href="#" target="_blank" title="" data-placement="bottom" rel="tooltip" data-original-title="Twitter">Twitter</a></li><li class="googleplusC edged"><a href="#" target="_blank" title="" data-placement="bottom" rel="tooltip" data-original-title="Google+">Google+</a></li><li class="linkedinC edged"><a href="#" target="_blank" title="" data-placement="bottom" rel="tooltip" data-original-title="LinkedIn">Google+</a></li><li class="pinterestC edged"><a href="#" target="_blank" title="" data-placement="bottom" rel="tooltip" data-original-title="Pinterest">Google+</a></li></ul>'
    $('.share').hover(
            function() {
                $(this).append(shareBlock);
                $('div.all-share').append(sharData);
                $('.all-share').click(function() {
                    return false;
                })
            },
            function() {
                $(this).children().last().remove();

            });





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
        $.get('/profile/notifications/friends_awaiting', function(response){
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
        if ($('div.over-block').length > 0) {
            $('header .top-scrip ul li').find('div').remove();
        }
        $(this).append(socialBlock);
        $('header .top-scrip ul li div').append(accountData);
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
    
    //Login user homepage slider
	$(function(){
		$('.fadein img:gt(0)').hide();
		setInterval(function(){$('.fadein :first-child').fadeOut().next('img').fadeIn().end().appendTo('.fadein');}, 3000);
	});


});

