<?php if (!empty($records)): ?>
    <div class="events-heading">Upcoming</div>
    <div class="event-slot-aera clearfix">
        <ul id="mycarousel" class="jcarousel-skin-tango">
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
