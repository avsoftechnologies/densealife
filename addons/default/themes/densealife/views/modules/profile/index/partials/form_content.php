<form method="post" id='form-create-event'>
<table style='width:100%;'>
    <tr>
        <td>
            <label for="title"><?php echo lang('eventsmanager:title_label'); ?> <span>*</span></label>
        </td>
    </tr>

    <tr>
        <td>
            <div class='input'><?php echo form_input('title', htmlspecialchars_decode($event->title), 'maxlength="255" id="title"') ?></div>
        </td>
    </tr>
    <tr>
        <td>
            <label for="slug"><?php echo lang('eventsmanager:slug_label'); ?> <span>*</span></label>
        </td>
    </tr>

    <tr>
        <td>
        <div class="input">
            <label><?php echo BASE_URL . $this->module_details['slug'] . '/'; ?><span id="slug"><?php echo $event->slug; ?></span></label>
        <?php echo form_hidden('slug', isset($event->slug) ? $event->slug : "", 'disabled="disabled"'); ?>
        </div>
    
        </td>
    </tr>

    <tr>
        <td>
            <label for="sub_category_id"><?php echo lang('sub_cat:category_label') ?>&nbsp;<span>*</span></label>
        </td>
    </tr>

    <tr>
        <td>
          <div class="input sub_category_id">
                <input type="hidden" name="category_id" value="<?php echo $category_id;?>"/>
                <?php echo form_dropdown('sub_category_id', array( '' => lang('cat:no_category_select_label') ) + $sub_categories, $event->sub_category_id) ?>
            </div>
    
        </td>
    </tr>

    <tr>
        <td>
            <label form='about'>About <span>*</span></label>
        </td>
    </tr>

    <tr>
        <td>
            <div class='input'><?php echo form_textarea(array( 'id' => 'about', 'name' => 'about', 'value' => isset($event->description) ? $event->description : '', 'rows' => 10 )); ?></div>
        </td>
    </tr>

    <tr>
        <td>
            <label form='description'><?php echo lang('eventsmanager:description_label'); ?> <span>*</span></label>
        </td>
    </tr>

    <tr>
        <td>
            <div class='input'><?php echo form_textarea(array( 'id' => 'description', 'name' => 'description', 'value' => isset($event->description) ? $event->description : '', 'rows' => 10 )); ?></div>
        </td>
    </tr>

    <tr>
        <td>
            <label for="start_date"><?php echo lang('eventsmanager:start_date_label'); ?> <span>*</span></label>
        </td>
    </tr>

    <tr>
        <td>
            <!-- Is "end_date_defined" set ? -->
            <?php
            if ( !isset($event->end_date_defined) ) {
                $event                   = ( object ) $event;
                $event->end_date_defined = 0;
            }
            ?>

            <div class="input">
                <?php
                echo form_input(array(
                    'class' => 'datepicker',
                    'name'  => 'start_date',
                    'id'    => 'start_date',
                    'value' => date($date_format, strtotime($event->start_date)),
                    'style' => '' ));
                ?>
            </div>

        </td>
    </tr>

    <tr>
        <td>
            <label for="time_label"><?php echo lang('eventsmanager:time_label'); ?>  <span>*</span></label>
        </td>
    </tr>

    <tr>
        <td>

            <div class="input">
                <?php
                echo form_dropdown('start_time_hour', time_range(0, 23), isset($event->start_date) ? date('H', strtotime($event->start_date)) : null, 'style="width:100px;"');
                ?>
                <b class="time_separator">:</b>
                <?php
                echo form_dropdown('start_time_minute', time_range(0, 59), isset($event->start_date) ? date('i', strtotime($event->start_date)) : null, 'style="width:100px;"');
                ?>
                <u style="cursor: pointer; <?php if ( $event->end_date_defined ): ?>display: none;<?php endif; ?>"  id="add_end_date"><?php echo lang('eventsmanager:add_end_date_label'); ?></u>
            </div>
            <input type="hidden" id="end_date_defined" name="end_date_defined" value="<?php echo isset($event->end_date_defined) ? $event->end_date_defined : false; ?>" />

        </td>
    </tr>
    
    <tr class="date-meta" id="end_date_form" <?php if ( !$event->end_date_defined ): ?>style="display: none;"<?php endif; ?>>
        <td>
        <label for="end_date"><?php echo lang('eventsmanager:end_date_label'); ?></label>
        <div class="input">
            <?php
            echo form_input(array(
                'class' => 'datepicker',
                'name'  => 'end_date',
                'id'    => 'end_date',
                'value' => date($date_format, strtotime($event->end_date)),
                'style' => '' ));
            ?>
            <div class='clear'></div>
            <label for='time_label'><?php echo lang('eventsmanager:time_label'); ?></label> &nbsp;
            <div class='clear'></div>
            <?php
            echo form_dropdown('end_time_hour', time_range(0, 23), isset($event->end_date) ? date('H', strtotime($event->end_date)) : null, 'style="width:100px;"');
            ?>
            &nbsp;<b class="time_separator">:</b>&nbsp;
            <?php
            echo form_dropdown('end_time_minute', time_range(0, 59), isset($event->end_date) ? date('i', strtotime($event->end_date)) : null, 'style="width:100px;"');
            ?>
            &nbsp;<u style="cursor: pointer;" id="remove_end_date"><?php echo lang('eventsmanager:delete_label'); ?></u>
        </div>
        </td>
    </tr>
    <tr>
        <td>

            <input type="hidden" id="author" name="author" value="<?php echo isset($event->author) ? $event->author : $this->current_user->id; ?>" />
            <div class="input">
                <input type="hidden" id="event-id" value="<?php echo isset($event->id) ? $event->id : null; ?>" />
            </div>

        </td>
    </tr>

    <tr>
        <td>
            <label for="comments"><?php echo lang('eventsmanager:comments_label'); ?></label>
        </td>
    </tr>

    <tr>
        <td>


            <div class="input"><?php echo form_dropdown('enable_comments', array( '1' => lang('eventsmanager:yes'), '0' => lang('eventsmanager:no') ), isset($event->enable_comments) ? $event->enable_comments : false); ?></div>
        </td>
    </tr>
    <tr>
        <td>
            <label for="place_clone"><?php echo lang('eventsmanager:place_label'); ?> <span>*</span></label>
        </td>
    </tr>

    <tr>
        <td>      
            <div>
                <span class="input">
                    <input type="text" id="place_clone" name="place" value="<?php echo isset($event->place) ? $event->place : ""; ?>" />
                </span>
                <div class='clear'></div>
                <input class='ml10' type="checkbox" id="show_map_clone" name="show_map_clone" value="1"
                       <?php if ( isset($event->show_map) && $event->show_map ) echo "checked"; ?> />
                &nbsp;<?php echo lang('eventsmanager:show_map_label'); ?>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <label for="pos_method"><?php echo lang('eventsmanager:pos_retrieve_label'); ?></label>
        </td>
    </tr>

    <tr>
        <td>

            <div>
                <?php echo form_radio('pos_method', 0, !isset($event->pos_lat) || !isset($event->pos_lng)); ?>
                &nbsp;<?php echo lang('eventsmanager:pos_auto_label'); ?>&nbsp;
                <?php echo form_radio('pos_method', 1, isset($event->pos_lat) && isset($event->pos_lng)); ?>
                &nbsp;<?php echo lang('eventsmanager:pos_man_label'); ?>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <label for="pos_lat"><?php echo lang('eventsmanager:position_label'); ?> <span>*</span></label>
        </td>
    </tr>

    <tr>
        <td>

            <div class="input">
                <?php echo lang('eventsmanager:latitude_label'); ?>&nbsp;:
                <input style='width:40px;' type="text" id="pos_lat" name="pos_lat" value="<?php echo isset($event->pos_lat) ? $event->pos_lat : ""; ?>" />
                &nbsp;<?php echo lang('eventsmanager:longitude_label'); ?>&nbsp;:
                <input style='width:40px;' type="text" name="pos_lng" value="<?php echo isset($event->pos_lng) ? $event->pos_lng : ""; ?>" />
            </div>
        </td>
    </tr>

    <tr>
        <td>

            <p><?php echo lang('eventsmanager:map_overview'); ?>&nbsp;
                <a id="map-refresh"><b><?php echo lang('eventsmanager:refresh_label'); ?></b></a>
            </p>
            <div class="input"><div id="map_canvas"></div></div>
        </td>
    </tr>
</table>
    <button>Save & Continue</button>
</form>
<div class="clear"></div>