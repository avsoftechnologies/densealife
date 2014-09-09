<fieldset>
    <ul>
        <li>
            <label for="place_clone"><?php echo lang('eventsmanager:place_label') ; ?> <span>*</span></label>
            <div>
                <span class="input">
                    <input type="text" id="place_clone" name="place_clone" value="<?php echo isset($event->place) ? $event->place : "" ; ?>" />
                </span>
                &nbsp;&nbsp;
                <input type="checkbox" id="show_map_clone" name="show_map_clone" value="1"
                       <?php if ( isset($event->show_map) && $event->show_map ) echo "checked" ; ?> />
                &nbsp;<?php echo lang('eventsmanager:show_map_label') ; ?>
            </div>
        </li>
        <li>
            <label for="pos_method"><?php echo lang('eventsmanager:pos_retrieve_label') ; ?></label>
            <div>
                <?php echo form_radio('pos_method', 0, !isset($event->pos_lat) || !isset($event->pos_lng)) ; ?>
                &nbsp;<?php echo lang('eventsmanager:pos_auto_label') ; ?>&nbsp;
                <?php echo form_radio('pos_method', 1, isset($event->pos_lat) && isset($event->pos_lng)) ; ?>
                &nbsp;<?php echo lang('eventsmanager:pos_man_label') ; ?>
            </div>
        </li>
        <li id="map_latlng_inputs">
            <label for="pos_lat"><?php echo lang('eventsmanager:position_label') ; ?> <span>*</span></label>
            <div class="input">
                <?php echo lang('eventsmanager:latitude_label') ; ?>&nbsp;:
                <input type="text" id="pos_lat" name="pos_lat" value="<?php echo isset($event->pos_lat) ? $event->pos_lat : "" ; ?>" />
                &nbsp;<?php echo lang('eventsmanager:longitude_label') ; ?>&nbsp;:
                <input type="text" name="pos_lng" value="<?php echo isset($event->pos_lng) ? $event->pos_lng : "" ; ?>" />
            </div>
        </li>
        <li>
            <p><?php echo lang('eventsmanager:map_overview') ; ?>&nbsp;
                <a id="map-refresh"><b><?php echo lang('eventsmanager:refresh_label') ; ?></b></a>
            </p>
            <div class="input"><div id="map_canvas"></div></div>
        </li>
    </ul>
</fieldset>