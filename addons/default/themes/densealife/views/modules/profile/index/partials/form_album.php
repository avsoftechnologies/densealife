<ul class="search-box clearfix">
    <li class="right"><button id="add-album" data-id="<?php echo $event->id;?>">Add Album</button></li>
</ul>

<div id="container-album-create-form" class="d-none">&nbsp;</div>
<div class="clear"></div>
<hr/>
<div class="clear"></div>

<?php if ( !empty($showDir) && !empty($albums) && count($albums['data']['folder'] )): ?>
    <ul class="videos ml15">
        <?php foreach ( $albums['data']['folder'] as $album ): ?>
            <?php if ( $album->file_count != 0 ): ?>
                <li class='wid-176'>
                    <a class="fancybox fancybox.ajax" href="/eventsmanager/album_images/<?php echo $album->id ; ?>" title='<?php echo $album->title;?>'>
                        <img src="/files/thumb/<?php echo $album->folder_image ; ?>/135/135/fit"  width="135" height="135"/>
                        <span class="name txt-ellpsis" style='text-align: center;'>
                            <span class='txt-up'><?php echo $album->name ; ?> </span>
                            <div class='clear'></div>
                            (<?php echo $album->file_count ; ?> Photos)
                        </span>
                    </a>
                </li>
            <?php endif ; ?>
        <?php endforeach ; ?>
    </ul>
<?php endif ; ?>
<div class="clear"></div>