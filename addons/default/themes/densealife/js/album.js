var album = {
    delete: function(album_id) {
        var x = confirm('Do you really want to delete this album ?');
        if (x) {
            $.get('/album/delete', {album_id: album_id}, function(response) {
                if (response.status === 'success') {
                    $('.album_' + album_id).fadeOut('slow');
                }
                ;
            }, 'json');
        }
    },
    add_photo: function(album_id) {
        window.scrollTo(0, 0);
        $('#container-album-create-form').load('/photo/upload?album_id=' + album_id, function() {
            $(this).slideDown('slow');
        });
    },
    delete_photo: function(photo_id) {
        $.get('/users/photo/delete', {photo_id: photo_id}, function(response){
            if(response.status==='success') {
                $('.photo_' + photo_id).fadeOut('slow');
            }
        }, 'json');
    }
    
};