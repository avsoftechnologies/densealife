var hashUpdate = function(curr_hash) {
    $('.wall').animate({'opacity': '0.3'});
    $.fancybox.showLoading();
    var page_to_load = curr_hash.substr(1);
    var splitten = window.location.pathname.split('/');
    var loadurl = '/' + splitten[1] + '/' + page_to_load +'/' + splitten[2];
     $(".wall").load(loadurl, function() {
        $('.wall').animate({'opacity': '1'});
        $.fancybox.hideLoading();
        $('.comman-links').find('li').each(function() {
            $(this).removeClass('active');
        });
        if(page_to_load.indexOf('/') > 0){
            mypage = page_to_load.substr(0, page_to_load.indexOf('/')-1);
        }else{
            mypage = page_to_load;
        }
        $('.page-'+mypage).addClass('active');
    }); 
};

$(document).ready(function() {
    $(window).bind('hashchange', function(e) {
        hashUpdate(e.target.location.hash);
    });
    window.location.hash && hashUpdate(window.location.hash);
    
    $('.view_more_comments').click(function(){
        var $_this = $(this);
        $_this.hide();
        $_this.parent('li').before('<li class="txt-center loading_more color-blue">Loading More...</li>');
        $.post('/comments/view_more',{'post_id': $_this.data('id'), 'offset' : $_this.data('offset')}, function(res){
            $_this.parent('li').siblings('.loading_more').remove();
            $_this.parent('li').before(res.html);
            if(res.remaining === 0){
                $_this.hide();
            }else{
                $_this.attr('data-offset', res.offset);
            }
        },'json');
    });
    $('body').on('keydown', '.form-post-comment', function(event) {
        if (event.keyCode == 13 && $(this).val() != '' && !event.shiftKey) {
            $(this).blur();
            $('.form-post-comment').attr('value', '');
            $(this).parent('form').submit();
        }

    });
    
    $('body').on('click', '.post-delete', function(){  
       var $_this = $(this);
       $.fancybox.showLoading();
       $.post('/comments/delete', {id: $_this.data('id')}, function(response) {
            if (response.status === 'success') {
                $('.li-' + $_this.data('id')).remove();
                $.fancybox.hideLoading();
            }
        }, 'json');
    });

    $('.wall-status').click(function() {
        if ($(this).data('type') == 'text') {
            $('.status-box-text').show();
            $('.status-box-media').hide();
        }
        if ($(this).data('type') == 'image-video') {
            $('.status-box-text').hide();
            $('.status-box-media').show();
            $('.status-box-text').after('<div class="status-box status-box-media"><form action="' + baseurl + 'eventsmanager/upload_wall_status" method="post" enctype="multipart/form-data" id="myForm"><label for="file">Filename:</label><input type="hidden" name="entry_id" value="' + $(this).data('event') + '"/><input type="hidden" name="title" value="' + $(this).data('title') + '"/><input type="file" name="file" id="file" multiple><br><input type="submit" name="submit" value="Submit"></form></div>')
        }
    });

    $('body').on('click', '.ctrl_trend', function() {
        var label = $(this).text();
        var entry = $(this).data('id');
        if (label == 'Star') {
            $(this).text('Unstar');
            if ($(this).hasClass('star')) {
                $(this).siblings('.star-count').text(parseInt($(this).siblings('.star-count').text()) + 1);
            }
        } else if (label == 'Unstar') {
            $(this).text('Star');
            if ($(this).hasClass('star')) {
                $(this).siblings('.star-count').text(parseInt($(this).siblings('.star-count').text()) - 1);
            }
        } else if (label == 'Follow') {
            $(this).text('Following');
            var selector = $('.count_follow_' + entry);
            selector.html(parseInt(selector.html()) + 1);
        } else if (label == 'Following') {
            $(this).text('Follow');
            var selector = $('.count_follow_' + entry);
            selector.html(parseInt(selector.html()) - 1);
        } else if (label == 'Add Favorite') {
            $(this).text('Favorite');
        } else if (label == 'Favorite') {
            $(this).text('Add Favorite');
        }
        $(this).siblings('form').submit();
    });

});
$('#myForm').ajaxForm({
    delegation: true, // for live response
    beforeSubmit: function() {
        $.fancybox.showLoading();
    },
    success: function(response) {
        $('.status-box-media').hide();
        $('.status-box-text').show();
        $('.media-form').remove();
        $('#response').append(response);
    },
    complete: function() {
        $.fancybox.hideLoading();
    }
});
$('.form-status').ajaxForm({
    type: 'POST',
    delegation: true, // for live response
    dataType: 'json',
    url: '/comments/create/eventsmanager',
    commentForm: function(response) {
        return '<form accept-charset="utf-8" method="post" class="form-status" action="' + this.url + '"><input type="hidden" value="' + response.entry + '" name="entry"><input type="hidden" value="' + response.comment_id + '" name="parent_id"><textarea class="form-post-comment " name="comment" rows="" cols="" onfocus="this.value =\'\'"></textarea></form>';
    },
    media: function(response) {
        if (response.media != '') {
            return '<a class="fancybox-effects-b pl10" data-fancybox-group="button" href="' + response.media.baseurl + 'files/large/' + response.media.filename + '"><img src="' + response.media.baseurl + 'files/wall/' + response.media.id + '" alt=""/></a>';
        } else {
            return '';
        }
    },
    beforeSubmit: function() {
        $.fancybox.showLoading();
        $('.form-post-comment').val('');
    },
    success: function(response) {
        $('#response').html('');
        $('.status-box-text').show();
        $('.status-box-media').hide().remove();
        $('.main-comment').val('');
        if (response.parent_id === 0) {
            content = '<div class="container"><div class="header"><div class="profile_pic">' + response.pic_creator + '</div><div class="post_title"><span class="display_name">' + response.user_name + '</span><br/><span class="time time-ago">' + response.time_ago + '</span></div></div><div class="comments">' + this.media(response) + '<span class="clear"></span><span class="fl">' + response.comment + '</span><span class="clear">&nbsp;</span><span class="comman-star stars">' + response.link_star + '</span><span><a href="/comments/share/' + response.comment_id + '" class="fancybox fancybox.ajax">Share</a></span></div><div class="comment-box"><ul><li><span>' + response.pic + '</span> <div class="status-aera children">' + this.commentForm(response) + '</div></li></ul></div></div><span class="seperator">&nbsp;</span>';
            if ($('.status-blog ul').siblings().length > 0) {
                $('.status-blog li:first').before('<li class="li-' + response.comment_id + '">' + content + '</li>');
            } else {
                $('.status-box').after('<ul class="status-blog"><li class="li-' + response.comment_id + '">' + content + '</li></ul>');
            }
        } else {
            content = '<div class="header"><div class="profile_pic"><a href="/user/ankit">' + response.pic_creator + '</a></div><div><span class="display_name">' + response.user_name + '</span>&nbsp;' + response.comment + '<br><span class="time time-ago">' + response.time_ago + '</span></div></div>';
            $('.li-' + response.parent_id).find('ul li:last').before('<li class="li-' + response.comment_id + ' mb10">' + content + '</li>');
        }
    },
    complete: function() {
        $.fancybox.hideLoading();
    }

});

$('.form-status-media').ajaxForm({
    type: 'POST',
    delegation: true, // for live response
    dataType: 'json',
    url: '../../comments/create_media/eventsmanager',
    commentForm: function(response) {
        return '<form accept-charset="utf-8" enctype="multipart/form-data" method="post" class="form-status-media" action="' + this.url + '"><input type="hidden" value="' + response.entry + '" name="entry"><input type="hidden" value="' + response.comment_id + '" name="parent_id"><textarea class="form-post-comment " name="comment" rows="" cols="" onfocus="this.value =\'\'"></textarea></form>'
    },
    beforeSubmit: function() {
        $.fancybox.showLoading();
        $('.form-post-comment').val('');
    },
    success: function(response) {
        $('.main-comment').val('');
        if (response.parent_id == 0) {
            var content = ' <span>' + response.pic + '</span><div class="status-aera"><span class="name">' + response.user_name + '</span><span><p>' + response.comment + '</p></span><span class="comman-star stars"><a href="#">4500</a><a href="#">Stars</a><a href="">Comments</a><a href="">Share</a></span></div><ul class="comments-hare"><li class="li-child-' + response.comment_id + '"><span>' + response.pic + '</span><div class="status-aera children">' + this.commentForm(response) + '</div></li></ul>';
            if ($('.status-blog ul').siblings().length > 0) {
                $('.status-blog li:first').before('<li class="li-' + response.comment_id + '">' + content + '</li>');
            } else {
                $('.status-box').after('<ul class="status-blog"><li class="li-' + response.comment_id + '">' + content + '</li></ul>')
            }
        } else {
            content = ' <span>' + response.pic + '</span><div class="status-aera"><span class="name">' + response.user_name + '</span><span><p>' + response.comment + '</p></span></div>';
            $('.li-' + response.parent_id).find('ul li:last').before('<li class="li-' + response.comment_id + '">' + content + '</li>')
        }


    },
    complete: function() {
        $.fancybox.hideLoading();
    }

});

$('.form-trend').ajaxForm({
    type: 'POST',
    delegation: true, // for live response
    dataType: 'json',
    beforeSubmit: function() {
        $.fancybox.showLoading();
    },
    success: function(response) {

        if (response.trend == '1') {
            if (response.action == '-1') {
                $('.btn-follow-' + response.entry).text('Follow');
            } else {
                $('.btn-follow-' + response.entry).text('Following');
            }
            window.location.reload();
        }

        if (response.trend == '2') {
            if (response.action == '-1') {
                $('.btn-favorite-' + response.entry).text('Add Favorite');
            } else {
                $('.btn-favorite-' + response.entry).text('Favorite');
            }
        }
        if (response.trend == '3') {
            $star_place_holder = $('.count_star_' + response.entry);
            star_count = parseInt($star_place_holder.html())
            if (response.action == '-1') {
                $star_place_holder.text(eval(star_count - 1))
            } else {
                $star_place_holder.text(eval(star_count + 1))
            }
        }
    },
    complete: function() {
        $.fancybox.hideLoading();
    }
});

$('#form-share').ajaxForm({
    type: 'POST',
    delegation: true, // for live response
    dataType: 'json',
    beforeSubmit: function() {
        $.fancybox.showLoading();
    },
    success: function(response) {
        if (response.status === 'success') {
            $.fancybox.close();
        }
    },
    complete: function() {
        $.fancybox.hideLoading();
    }
});