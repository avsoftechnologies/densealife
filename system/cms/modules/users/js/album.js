$(document).ready(function() {
    $('body').on('click','#add-album',function() {
        id = ''; // id of event or interest
        if($(this).data('id')){
            id = $(this).data('id');
        }
        $('#container-album-create-form').load('/album/create/'+id, function() {
            $(this).slideDown('slow');
        });
    });
    
    $('body').on('click','#add-photo-video',function() {
        $('#container-album-create-form').load('/photo/upload', function() {
            $(this).slideDown('slow');
        });
    });
});

$('#form-add-album').ajaxForm({
    type: 'POST',
    delegation: true, // for live response
    dataType: 'json',
    beforeSubmit: function() {
        $.fancybox.showLoading();
    },
    success: function(response) {
        if(response.status){
            $('#container-album-create-form').load('/photo/upload');
            
        }else{
            $('.error-msg').remove();
            $('#container-album-create-form').before(response.message);
        }
        
    },
    error: function(response){
        $('#container-album-create-form').before(response.message);
    },
    complete: function() {
        $.fancybox.hideLoading();
    }, 
    
});


