<ul class="search-box clearfix">
    <li class="right"><button id="add-album" data-id="<?php echo $event->id;?>">Add Album</button></li>
</ul>

<div id="container-album-create-form" class="d-none">&nbsp;</div>
<div class="clear"></div>
<hr/>
<div class="clear"></div>

<?php if ( !empty($showDir) && !empty($albums) && count($albums['data']['folder'] )): ?>
    <ul class="form_album videos ml15">
        <?php foreach ( $albums['data']['folder'] as $album ): ?>
            <?php if ( $album->file_count != 0 ): ?>
                <li class='txt-center album_<?php echo $album->id ; ?>'>
                    <a class="fancybox fancybox.ajax" href="/eventsmanager/album_images/<?php echo $album->id ; ?>" title='<?php echo $album->title;?>'>
                        <img src="/files/thumb/<?php echo $album->folder_image ; ?>/135/135/fit"  width="135" height="135"/>
                    </a>
                        <span class="name txt-ellpsis" style='text-align: center;'>
                            <span class='txt-up'><?php echo $album->name ; ?> </span>
                            <div class='clear'></div>
                            (<?php echo $album->file_count ; ?> Photos)
                        </span>
                    
                    <span class="action d-none">
                        <a href="/eventsmanager/album_images/<?php echo $album->id ; ?>" class="fs10 fancybox fancybox.ajax">Open</a> | <a href="javascript:album.add_photo('<?php echo $album->id;?>')" class="fs10">Add Photos</a>  | <a href="javascript:album.delete('<?php echo $album->id;?>')" class="fs10">Delete</a>
                    </span>
                </li>
            <?php endif ; ?>
        <?php endforeach ; ?>
    </ul>
<?php endif ; ?>
<div class="clear"></div>
<script>
    
$('.form_album').on('mouseover', '.videos li', function() {
    $(this).css({'border': '1px solid black', 'padding': '5px', 'z-index': '1000'});
    $(this).children('.action').removeClass('d-none');
});

$('.form_album').on('mouseout', '.videos li', function() {
    $(this).css({'border': 'none', 'padding': '0px', 'z-index': 0});
    $(this).children('.action').addClass('d-none');
});
</script>