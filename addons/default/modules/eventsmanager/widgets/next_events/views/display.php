<?php if(!empty($agenda_widget)) { ?>
<ul id="next-events">
	<?php foreach($agenda_widget as $event_widget): ?>
		<li>
			<img src="{{ eventsmanager:path }}img/date.png" />
			<?php
				$date = new Datetime($event_widget->start_date);
				echo anchor('eventsmanager/'.$event_widget->slug, $event_widget->title).', <span class="date_label">'.$date->format($date_format).'</span>';
			?>
		</li>
	<?php endforeach; ?>
</ul>
<p id="see-all">&rArr;&nbsp;<?php echo anchor('eventsmanager', lang('widget_nextevents:see_all')); ?></p>
<?php } else { ?>
	<p>{{ helper:lang line="widget_nextevents:no_upcoming_event_error" }}</p>
<?php } ?>
<style>
	ul#next-events { margin-left: 0; margin-bottom: 10px; }
	ul#next-events li { list-style: none; font-size: 1em; padding: 5px 0; margin: 0; }
	ul#next-events li img { vertical-align: top; margin-right: 3px; }
	ul#next-events li span.date_label { font-size: 0.85em; }
</style>