<?php if ($images): ?>
    <div class="album_images">
        <ul>
            <?php foreach ($images as $image): ?>
                <li>
                    <a class="fancybox fl mr10" data-fancybox-group="gallery" href="<?php echo $image->path; ?>">
                        <img src="{{url:site}}files/medium/<?php echo $image->id; ?>/fit" alt="" width="200" height="200"/>
                    </a>
                    <span class="action d-none">
                        <a href="javascript:album.delete('<?php echo $album->id; ?>')" class="fs10">Delete</a>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<script>
    $('body').on('mouseover', '.album_images a', function() {
        $(this).css({'border': '1px solid black', 'padding': '5px', 'z-index': '1000', 'position': 'relative'});
        $(this).children('.action').removeClass('d-none');
    });

    $('body').on('mouseout', '.album_images a', function() {
        $(this).css({'border': 'none', 'padding': '0px', 'z-index': 0});
        $(this).children('.action').addClass('d-none');
    });
</script>
