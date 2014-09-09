<div class="comman-heading">Videos</div>
<div class="clear"></div>
<?php
if (!empty($youtube_videos)):
    foreach ($youtube_videos as $key => $value) :
        ?>
        <iframe width="280" height="158" src="http://www.youtube.com/embed/<?php echo $value; ?>" frameborder="0" allowfullscreen></iframe>
    <?php endforeach; ?>
<?php else: ?>
    No video files
<?php endif; ?>