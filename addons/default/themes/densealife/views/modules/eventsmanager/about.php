<?php $module_path = BASE_URL . $this->module_details['path']; ?>
<div class="comman-heading">Join the Conversation</div>
<div class="clear">&nbsp;</div>
<div class="f-bold">About</div>
<p><?php echo $event->about ; ?></p>
<div class="clear">&nbsp;</div>

<div class="f-bold">Description</div>
<p><?php echo $event->description ; ?></p>
<div class="clear">&nbsp;</div>

<?php if($event->start_time && $event->start_time!='0000-00-00 00:00:00'):?>
<div class="f-bold">Date</div>
<p> <?php
    if ( $event->end_date_defined && isset($event->end_date) ) {
        if ( date('Y-m-d', strtotime($event->start_date)) != date('Y-m-d', strtotime($event->end_date)) ) {
            echo lang('eventsmanager:from_date_label') . '&nbsp' . format_date($event->start_date) . '&nbsp' .
            lang('eventsmanager:at_time_label') . '&nbsp' . $event->start_time . '&nbsp - ' .
            lang('eventsmanager:to_date_label') . '&nbsp' . format_date($event->end_date) . '&nbsp' .
            lang('eventsmanager:at_time_label') . '&nbsp' . $event->end_time ;
        } else {
            echo lang('eventsmanager:on_date_label') . '&nbsp' . format_date($event->start_date) . '&nbsp' .
            lang('eventsmanager:from_time_label') . '&nbsp' . $event->start_time . '&nbsp -' .
            lang('eventsmanager:to_time_label') . '&nbsp' . $event->end_time ;
        }
    } else {
        echo lang('eventsmanager:on_date_label') . '&nbsp' . format_date($event->start_date) . '&nbsp' .
        lang('eventsmanager:at_time_label') . '&nbsp' . $event->start_time ;
    }
    ?></p>
<?php endif; ?>
<?php if($event->website):?>
<div class="clear">&nbsp;</div>
<div class="f-bold">Website</div>
<div><?php echo $event->website; ?></div>
<?php endif;?>
<?php if($event->affiliations):?>
<div class="clear">&nbsp;</div>
<div class="f-bold">Affiliations</div>
<div><?php echo $event->affiliations; ?></div>
<?php endif;?>
<?php if($event->place):?>
<div class="clear">&nbsp;</div>
<div><span class='f-bold'>Location</span>  - <?php echo $event->place; ?></div>
<?php endif;?>
<?php if ( $event->show_map ): ?>
    <div id="event-map">
        <h6><img src="<?php echo $module_path . '/img/gmaps.png' ?>" />{{ helper:lang line="eventsmanager:map_label" }}</h6>
        <div id="map_canvas"></div>
    </div>
<?php endif ; 
