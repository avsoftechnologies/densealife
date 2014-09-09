<script>
    $("#select-pic").sticky({ topSpacing: 0 });
    $('.link-create-album').button()
            .click(function(){
       $('#tab-album').trigger('click');         
    });
        
       $('button').click(function() {
               if ($('.picture_id:checked').val()) {
                   image = $('.picture_id:checked').val();
                   thumb = ((this.id == 'save_thumb') ? 'thumb' : 'cover_photo');

                   $.post('/eventsmanager/save_thumb', {picture_id: image, type: thumb}, function(response) {
                       if (response.status == 'success') {
                           if (thumb == 'thumb') {
                               alert('Image saved as thumbnail');
                           }else{
                               alert('Image saved as coverphoto');
                           }
                       }
                   }, 'json')

               } else {
                   alert('Please select an image to save as ' + ((this.id == 'save_thumb') ? 'thumbnail' : 'cover photo'));
               }
               return false;
           });
    
//    $('#form-image').ajaxForm({
//    type: 'POST',
//    delegation: true, // for live response
//    dataType: 'json',
//    beforeSubmit: function() {
//        $.fancybox.showLoading();
//    },
//    success: function(response) {
//        if(response.status = 'success'){
//            $('#tabs').enableTab(2);
//            $( "#tab-coverphoto" ).trigger( "click" );
//        }
//    }, 
//    error: function(){
//    },
//    complete: function() {
//        $.fancybox.hideLoading();
//    }
//});
</script>

<?php if ( !empty($pictures) ) :?>
<form method="post" action="/eventsmanager/save_thumb" id="form-image" onsubmit="return false; ">
    <h3>Select an image to make a thumbnail </h3>
    <div id="select-pic" style="width:47%; text-align: right;">
    <button id="save_thumb">Save as thumbnail</button>
    &nbsp;<button id="save_cp">Save as CoverPhoto</button>
  </div>
<ul id="folder-content" class="videos">
    <?php
    $check = '';
        foreach ( $pictures as $pic ) {
            if ( $check == '' ) {
                $check = site_url('files/thumb/' . $pic->id . '/500/200/fit');
            }
            echo '<li>'
            . '<a class="fancybox fl mr10" data-fancybox-group="gallery" href="' . site_url(str_replace('{{ url:site }}', '', $pic->path)) . '">'
            . img(array( 'src' => 'files/medium/' . $pic->id . '/fit', 'alt' => $pic->name, 'title' => $pic->name . ' -- ' . $pic->description, 'filename' => $pic->filename ))
            . '</a><div class="clear"></div>'
            . '<div style="text-align:center;">' . form_radio(array( 'class'       => 'picture_id',
                'name'        => 'picture_id',
                'value'       => $pic->id,
                'filename'    => $pic->filename,
                'data-width'  => $pic->width,
                'data-height' => $pic->height,
                'checked'     => isset($event->picture_id) && $event->picture_id == $pic->id ? true : false )) . '</div>'
            . '</li>';
        }
    ?>
</ul>
</form>
<?php else:?>
You don't have any image file to be set in thumbnail or coverphoto. Please click on the button below to create album. 
<br/>
<center>
    <a href='javascript:void(0)' class='link-create-album'>Create Album</a>
</center>
<?php endif;?>
