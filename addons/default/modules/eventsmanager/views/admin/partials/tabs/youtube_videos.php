<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('.add-box').click(function() {
            var n = $('.text-box').length + 1;
            var box_html = $('<p class="text-box"><label for="box' + n + '">Video <span class="box-number">' + n + '</span></label> <input type="text" name="youtube_videos[]" value="" id="box' + n + '" /> <a href="#" class="remove-box">Remove</a></p>');
            box_html.hide();
            $('p.text-box:last').after(box_html);
            box_html.fadeIn('slow');
            return false;
        });

        $('.my-form').on('click', '.remove-box', function() {
            $(this).parent().css('background-color', '#FF6C6C');
            $(this).parent().fadeOut("slow", function() {
                $(this).remove();
                $('.box-number').each(function(index) {
                    $(this).text(index + 1);
                });
            });
            return false;
        });
    });
</script>
<div class="my-form">
    <?php
    if (!empty($event->youtube_videos)):
        foreach (unserialize($event->youtube_videos) as $key => $value) :
            ?>
            <p class="text-box">
            <label for="box<?php echo $key + 1; ?>">Video <span class="box-number"><?php echo $key + 1; ?></span></label>
            <input type="textl" name="boxes[]" id="box<?php echo $key + 1; ?>" value="<?php echo $value; ?>" />
            <?php echo ( 0 == $key ? '<a href="#" class="add-box">Add More</a>' : '<a href="#" class="remove-box">Remove</a>' ); ?>
        </p>
        <?php
    endforeach;
else:
    ?>
    <p class="text-box">
    <label for="box1">Video <span class="box-number">1</span></label>
    <input type="text" name="youtube_videos[]" value="" id="box1" />
    <a class="add-box" href="#">Add More</a>
    </p>
<?php endif; ?>
</div>