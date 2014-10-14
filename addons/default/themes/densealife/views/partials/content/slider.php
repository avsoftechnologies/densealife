<?php if (!empty($records)): ?>
<div class="comman-heading"><?php echo $slider_title;?> <button>View All</button></div>
    <!--<div class="events-heading"><?php echo $slider_title;?></div>-->
    <div class="event-slot-aera clearfix">
        <ul id="<?php echo $widget_id;?>" class="jcarousel-skin-tango">
            <?php foreach ($records as $record): ?>
                <li>
                    <div class="event-slot-image">
                        <a href="{{ url:site uri='eventsmanager/<?= $record->slug ?>' }}" title="<?php echo $record->title; ?>" >{{ eventsmanager:thumb name="<?php echo $record->thumbnail; ?>" }}</a>
                    </div>
                    <span class="events-name">
                        <?php echo $record->title; ?>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
