var hashUpdate = function(curr_hash) {
    $('.wall-main').animate({
        'opacity': '0.3'
    });
    $.fancybox.showLoading();
    var page_to_load = curr_hash.substr(1);
    var loadurl = '/densealife-page/' + page_to_load;
    $(".wall-main").load(loadurl, function() {
        $('.wall-main').animate({
            'opacity': '1'
        });
        $.fancybox.hideLoading();
        $('.comman-links').find('li').each(function() {
            $(this).removeClass('active');
        });
        if (page_to_load.indexOf('/') > 0) {
            mypage = page_to_load.substr(0, page_to_load.indexOf('/') - 1);
        } else {
            mypage = page_to_load;
        }
        $('.page-' + mypage).addClass('active');
    });
};
ENTRY_TYPE = 'event';
$(document).on('change', '.drpdwn_sub_category_id', function() {
    page_to_load = $(this).siblings('#entry_type').val() == 'interest' ? 'i-interests' : 'events';
    window.location.hash = '#' + page_to_load + '/' + $(this).val();
});

$(document).ready(function() {
    
    if (window.location.hash) {
        prefix = window.location.hash.replace(/^.*?#/, '').substring(0, 2); // get the prefix of hash string eg: #i-trending will return i-

        if (prefix == 'i-') {
            $('.link-header-event').addClass('d-none');
            $('.link-header-interest').removeClass('d-none');
        } else {
            $('.link-header-event').removeClass('d-none');
            $('.link-header-interest').addClass('d-none');
        }
    } else {
        $('.link-header-event').removeClass('d-none');
        $('.link-header-interest').addClass('d-none');
    }

    $("#tabs").tabs({
        beforeLoad: function(event, ui) {
            ui.jqXHR.error(function() {
                ui.panel.html(
                    "Couldn't load this tab. We'll try to fix this as soon as possible. " +
                    "If this wouldn't be a demo.");
            });
        }
    });
    $(window).bind('hashchange', function(e) {
        prefix = window.location.hash.replace(/^.*?#/, '').substring(0, 2);
        if (prefix == 'i-') {
            $('.link-header-event').addClass('d-none');
            $('.link-header-interest').removeClass('d-none');
        } else {
            $('.link-header-event').removeClass('d-none');
            $('.link-header-interest').addClass('d-none');
        }
        hashUpdate(e.target.location.hash);
    });
    window.location.hash && hashUpdate(window.location.hash);
    var RECENTLY_CREATED_EVENT_ID;
    if (RECENTLY_CREATED_EVENT_ID != 'undefined' && RECENTLY_CREATED_EVENT_ID == '') {
        $('#tabs').disableTab(1).disableTab(2);
    }

    $('.drpdwn_category_id').change(function() {
        $.post('/eventsmanager/get_sub_categories', {
            cat_id: $(this).val()
        }, function(response) {
            $.each(response, function(k, v) {
                $('.drpdwn_sub_category_id').append(new Option(v, k));
            });
        }, 'json');
    });


    $("#start_date").datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        onClose: function(selectedDate) {
            $("#end_date").datepicker("option", "minDate", selectedDate);
        }
    });
    $("#end_date").datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        onClose: function(selectedDate) {
            $("#start_date").datepicker("option", "maxDate", selectedDate);
        }
    });
});
$('#form-create-event').ajaxForm({
    type: 'POST',
    delegation: true, // for live response
    dataType: 'json',
    url: '',
    beforeSubmit: function() {
        $.fancybox.showLoading();
    },
    success: function(response) {
        var body = $("html, body");
        body.animate({
            scrollTop: 0
        }, '500', 'swing', function() {
            if (response.status == 'failure') {
                $('.error-tab-1').html('<ul class="ml15" style="color:red;">' + response.message + '</ul>').fadeIn();
            } else {
                //$('#tabs').enableTab(1).enableTab(2);
                $("#tab-album").trigger("click");
                $('div.view-event').removeClass('d-none').addClass('d-block');
            }
        });
    },
    error: function() {},
    complete: function() {
        $.fancybox.hideLoading();
    }
});

$('#form-crop-image').ajaxForm({
    type: 'POST',
    delegation: true, // for live response
    dataType: 'json',
    url: '/eventsmanager/save_thumbnail',
    beforeSubmit: function() {
        $.fancybox.showLoading();
    },
    success: function(response) {
        var body = $("html, body");
        body.animate({
            scrollTop: 0
        }, '500', 'swing', function() {
            if (response.status == 'failure') {
                $('.error-tab-1').html('<ul class="ml15" style="color:red;">' + response.message + '</ul>').fadeIn();
            } else {
                $('#tabs').enableTab(2);
                $("#tab-thumbnail").trigger("click");
            }
        });
    },
    error: function() {},
    complete: function() {
        $.fancybox.hideLoading();
    }
});


$('#frm-sub-cat-filter').ajaxForm({
    type: 'POST',
    delegation: true, // for live response
    dataType: 'json',
    beforeSubmit: function() {
        $.fancybox.showLoading();
    },
    success: function(response) {
        alert('hello');
    },
    error: function() {},
    complete: function() {
        $.fancybox.hideLoading();
    }
});