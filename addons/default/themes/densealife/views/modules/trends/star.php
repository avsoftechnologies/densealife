<?php if($is_following):
 echo $this->trends->display_stars();
else: ?>
<?php echo form_open("trends/create/{$module}",'class="form-star d-none"'); ?>
<noscript><?php echo form_input('d0ntf1llth1s1n', '', 'style="display:none"'); ?></noscript>
<?php echo form_hidden('entry', $entry_hash); ?>
<?php echo form_hidden('trend', Trends::TREND_STAR);?>
<?php echo form_close();?>
<?php if($display):
echo $this->trends->display_stars();
endif ; ?>
<a href="javascript:void(0);" onclick="$('.form-star').submit()"><?php echo lang('trends:star_label');?></a>
<?php endif; ?>
