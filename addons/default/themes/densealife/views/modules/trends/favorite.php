<?php if($is_following): ?>
<button class="common disabled"><?php echo lang('trends:fav_add_label');?></button>
<?php else: ?>
<?php echo form_open("trends/create/{$module}"); ?>
<noscript><?php echo form_input('d0ntf1llth1s1n', '', 'style="display:none"'); ?></noscript>
<?php echo form_hidden('entry', $entry_hash); ?>
<?php echo form_hidden('trend', Trends::TREND_FAVOURITE);?>
<button class="btn-color btn-color common" type="submit"><?php echo lang('trends:fav_label');?></button>
<?php echo form_close();?>
<?php endif; ?>
