<?php echo form_open("trends/create",'class="form-trend d-none" id="form-star-'.$entry_id.'"' ); ?>
<?php echo form_hidden('data', $data); ?>
<?php echo form_close();?>
<?php if ( $variation == 'icon' ): ?>
<a href="javascript:void(0);" onclick="$('#form-star-<?php echo $entry_id;?>').submit();" class="float-left star-aera">Star</a>  
<?php else: ?>
    <a href="#" class="star-count">
        <?php echo $star_count; ?></a>Stars 
        <a href="javascript:void(0);" class='ctrl_trend star'><?php echo $text; ?></a>
<?php endif; ?>
