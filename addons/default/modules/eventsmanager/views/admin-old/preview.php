<h1><?php echo lang('eventsmanager:event_label') . '&nbsp;:&nbsp;' . $event->title; ?></h1>

<p style="float:left; width: 40%;">
	<?php echo anchor('eventsmanager/'.$event->slug, NULL, 'target="_blank"'); ?>
</p>

<p style="float:right; width: 40%; text-align: right;">
	<?php echo anchor('admin/eventsmanager/manage/'. $event->id, lang('global:edit'), ' target="_parent"'); ?>
</p>

<iframe id="event-preview" src="<?php echo site_url('eventsmanager/'.$event->slug); ?>" width="99%" height="600"></iframe>