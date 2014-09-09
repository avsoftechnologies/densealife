<?php $module_path = BASE_URL . $this->module_details['path'] ; ?>
<!-- Div containing all events -->
<div>
    <?php
    if ( !empty($sorted_events) ) {
        foreach ( $sorted_events as $period => $events ) {
            if ( !empty($events) ) {
                if ( strpos($period, "year-") === false ) {
                    ?>
                    <h4 id="<?php echo $period ; ?>" class="period"><?php echo lang('eventsmanager:' . $period . '_label') ; ?></h4>
                <?php } else { ?>
                    <h4 id="<?php echo $period ; ?>" class="period"><?php echo substr($period, 5) ; ?></h4>
                <?php
                }
            }
            foreach ( $events as $event ) {
                ?>
                <table class="event-container">
                    <tr>
                        <td class="event-picture">
                            <a href="<?php echo site_url($this->module) . '/' . $event->slug ; ?>">
                                <?php
                                if ( is_file(UPLOAD_PATH . 'files/' . $event->thumbnail) ) :
                                    echo img(array( 'src' => UPLOAD_PATH . 'files/' . $event->thumbnail )) ;
                                elseif ( isset($event->picture_id) ) :
                                    echo img(array( 'src' => 'files/thumb/' . $event->picture_id )) ;
                                else :
                                    echo img(array( 'src' => $module_path . '/img/event.png' )) ;
                                endif ;
                                ?>
                            </a>
                        </td>
                        <td class="event-content">
                            <a href="<?php echo site_url($this->module) . '/' . $event->slug ; ?>"><b><?php echo $event->title ; ?></b></a><br/>
                            <img src="<?php echo $module_path . '/img/date.png' ?>" />
                            <?php echo format_date($event->start_date) ; ?>
                            &nbsp;
                            <img src="<?php echo $module_path . '/img/clock.png' ?>" />
                            <?php echo $event->start_time ; ?>
                            <p><?php echo textSumUp(strip_tags($event->description), 150) ; ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2" style="text-align: right;">
                            <?php echo $this->load->view('eventsmanager/partials/follow', array( 'slug' => $event->slug )) ; ?>
                        </th>
                    </tr>
                </table>
            <?php
            }
        }
    } else {
        if ( $this->method == 'index' ) {
            ?>
            <p>{{ helper:lang line="eventsmanager:no_upcoming_event_error" }}</p>
        <?php } else {
            ?>
            <p>{{ helper:lang line="eventsmanager:no_past_event_error" }}</p>
    <?php
    }
}
?>
</div>
