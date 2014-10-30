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
    
    //Login user homepage slider
	$(function(){
		$('.fadein img:gt(0)').hide();
		setInterval(function(){$('.fadein :first-child').fadeOut().next('img').fadeIn().end().appendTo('.fadein');}, 3000);
	});


});

