$('#social').ajaxForm({
    type: 'POST',
    delegation: true, // for live response
    dataType: 'json',
    url: '/profile/social/create',
    beforeSubmit: function() {
         $('.social-user-profile').animate({'opacity': '0.3'});
        $.fancybox.showLoading();
    },
    success: function(response) {
        if(response.status =='success'){
            $('.msg').not(':visible').text('Socal links saved !!!').css({'color': 'green'}).slideDown()
        }
    }, 
    error: function(){
    },
    complete: function() {
        $('.social-user-profile').animate({'opacity': '1'});
        $.fancybox.hideLoading();
    }
});