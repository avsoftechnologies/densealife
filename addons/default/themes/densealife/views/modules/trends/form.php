<?php echo form_open("trends/create/{$module}"); ?>
<noscript><?php echo form_input('d0ntf1llth1s1n', '', 'style="display:none"'); ?></noscript>
<?php echo form_hidden('entry', $entry_hash); ?>

<button class="btn-color common" type="submit">Follow</button>
<?php echo form_close();?>