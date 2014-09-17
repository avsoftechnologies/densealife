<?php if ($events): ?>
    <div class="comman-box">
        <span class="heading-comman">Friends Events</span>
        <ul class="friends clearfix">
            <?php foreach ($events as $event): ?>
                <li>
                    <a href="{{ url:site uri='eventsmanager/<?= $event->slug ?>' }}" title="<?= $event->title ?>" >{{ eventsmanager:thumb name="<?= $event->thumbnail ?>" }}</a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>