<?php if ($this->method == 'manage' ): ?>
<style type="text/css">
    .coverphoto{
        width: 1014px;
        height: 300px;
        border: 1px solid black;
        margin: 10px auto;
    }
</style>

<script type="text/javascript">
    $(function() {
        $(".coverphoto").CoverPhoto({
            currentImage: "<?php echo isset($event->cover_photo) ? $event->cover_photo : null ; ?>",
            editable: true
        });
        $(".coverphoto").bind('coverPhotoUpdated', function(evt, dataUrl) {
            //$(".output").empty();
            $("<img>").attr("src", dataUrl).append(".coverphoto");

            //        $.get('admin/eventsmanager/save_cover_photo',{img:dataUrl},function(data){
            //            alert('saved');
            //        });
            //    return false;     
            $('#cover_photo').val(dataUrl);
        });
    });
</script>
<fieldset>
    <div class="coverphoto"></div>
    <input type="hidden" name="cover_photo" id="cover_photo" value="<?php echo isset($event->cover_photo) ? $event->cover_photo : null ; ?>" />
    <br class="clear-both" />    
</fieldset>
<?php endif; ?>