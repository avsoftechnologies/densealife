<fieldset id="filters">
	
	<legend><?php echo lang('global:filters'); ?></legend>
	
	<?php echo form_open('admin/eventsmanager/ajax_filter'); ?>
	
		<ul>  
			<li>
        		<?php echo lang('eventsmanager:status_label') . " :&nbsp;";
					echo form_dropdown('f_status', array(
						0 => lang('global:select-all'),
						'draft' => lang('eventsmanager:draft_label'),
						'live' => lang('eventsmanager:published_label'))
					);
					?>
    		</li>
			
			<li><?php echo form_input('f_keywords'); ?></li>
			<li><?php echo anchor(current_url() . '#', lang('buttons:cancel'), 'class="cancel"'); ?></li>
		</ul>
	<?php echo form_close(); ?>
</fieldset>