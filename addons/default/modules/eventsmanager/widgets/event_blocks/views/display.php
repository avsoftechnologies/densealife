<?php if(!empty($agenda_widget)) : ?>
<div class="events-heading">Heading</div>
    <div class="event-slot-aera clearfix">
        <ul>
            <?php foreach($agenda_widget as $event_widget): ?>
            <li>
                <div class="event-slot-image"><img src="images/event-image.png" alt=""></div>
                <span class="stars">Stars Rating</span> <span><?php echo $event_widget->title;?></span> <span>Category-Comedy</span> 
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif;?>