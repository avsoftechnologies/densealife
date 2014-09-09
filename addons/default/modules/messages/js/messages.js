$('#frm-create-message').ajaxForm({
    type: 'POST',
    delegation: true, // for live response
    dataType: 'json',
    beforeSubmit: function() {
        $.fancybox.showLoading();
    },
    success: function(response) {
        if (response.status == 'success') {
            $('#message-window').append(response.message);
            var objDiv = $('#message-window');
            if (objDiv.length > 0) {
                objDiv[0].scrollTop = objDiv[0].scrollHeight;
            }
            $('#status').val('');
        } else if(response.status == 'redirect') {
            window.location.href = '/messages/' + response.to;
        }
    },
    error: function() {
    },
    complete: function() {
        $.fancybox.hideLoading();
    }
});

