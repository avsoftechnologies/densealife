<?php if ($images): $rand = rand_string(10); ?>
    <ul style='width:840px;' class="album_images">
        <?php foreach ($images as $image): ?>
            <li style="float:left;" class="photos">
                <div class="txt-center">
                    <span class="fl">
                        <a class="fancybox fl mr10" data-fancybox-group="gallery_<?php echo $rand; ?>" href="<?php echo $image->path; ?>">
                            <img src="{{url:site}}files/medium/<?php echo $image->id; ?>" alt="" width="200" height="200"/>
                        </a>
                    </span>
                    <br/>
                    <span class="action d-none">
                        <a href="javascript:fullsize('<?php echo $image->path; ?>')">Full Screen</a> | <a href="javascript:remove('<?php echo $image->id; ?>')">Remove</a>
                    </span>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php
 endif;
 ?>

<script>
    $('body').on('mouseover', '.album_images .photos', function() {
        $(this).children('.action').removeClass('d-none');
    });

    $('body').on('mouseout', '.album_images .photos', function() {
        $(this).children('.action').addClass('d-none');
    });
</script>
