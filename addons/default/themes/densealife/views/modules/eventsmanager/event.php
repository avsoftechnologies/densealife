<?php $module_path = BASE_URL . $this->module_details['path'];  ?>
<h2><?php echo $event->title; ?></h2>
<?php echo $this->load->view('partials/follow',array('slug' => $event->slug));?>
<img src="<?php echo $event->cover_photo;?>" alt ="<?php echo $event->title; ?>" width="700"/>
<br />
<?php if (date ('Y-m-d H:i') < $event->end_date): ?>
<?php echo anchor ($this->module, "&larr;&nbsp;" . lang ('eventsmanager:back_label')); ?>
<?php else: ?>
<?php echo anchor ($this->module . '/past', "&larr;&nbsp;" . lang ('eventsmanager:back_label')); ?>
<?php endif; ?>
<?php if (group_has_role ('eventsmanager', 'frontend_editing')): ?>
<a href="<?php echo site_url ($this->module . '/edit/' . $event->slug); ?>" class="btn edit-event">{{ helper:lang line="eventsmanager:edit_event_button" }}</a>
<?php endif; ?>
<!-- Event content -->
<div id="event-content">
    <div id="event-metadata">
        <table id="prez">
            <tr>
                <td id="picture">
                    <?php if (isset ($event->picture_filename)) : ?>
                    <div id="cloak"><?php echo img (array('id' => $event->slug, 'src' => UPLOAD_PATH . 'files/' . $event->picture_filename, 'style' => 'max-width: 1000px; max-height: 700px;')); ?></div>
                    <a href="#<?php echo $event->slug; ?>">
                    <?php  endif;
                    if (is_file (UPLOAD_PATH . 'files/' . $event->thumbnail)) :
                            echo img (array('src' => UPLOAD_PATH . 'files/' . $event->thumbnail));
                    elseif (isset ($event->picture_id)) :
                            echo img (array('src' => 'files/thumb/' . $event->picture_id . '/150'));
                    else :
                            echo img (array('src' => $module_path . '/img/event.png'));
                    endif;
                    if (isset ($event->picture_filename)) :?>
                    </a>
                    <?php endif; ?>
                </td>
                <td id="metadata">
                    <h6><img src="<?php echo $module_path . '/img/date.png' ?>" />{{ helper:lang line="eventsmanager:date_time_label" }}</h6>
                    <p>
                        <?php
                            if ($event->end_date_defined && isset ($event->end_date))
                            {
                                    if (date ('Y-m-d', strtotime ($event->start_date)) != date ('Y-m-d', strtotime ($event->end_date)))
                                    {
                                            echo lang ('eventsmanager:from_date_label') . '&nbsp' . format_date ($event->start_date) . '&nbsp' .
                                            lang ('eventsmanager:at_time_label') . '&nbsp' . $event->start_time . '&nbsp' .
                                            lang ('eventsmanager:to_date_label') . '&nbsp' . format_date ($event->end_date) . '&nbsp' .
                                            lang ('eventsmanager:at_time_label') . '&nbsp' . $event->end_time;
                                    }
                                    else
                                    {
                                            echo lang ('eventsmanager:on_date_label') . '&nbsp' . format_date ($event->start_date) . '&nbsp' .
                                            lang ('eventsmanager:from_time_label') . '&nbsp' . $event->start_time . '&nbsp' .
                                            lang ('eventsmanager:to_time_label') . '&nbsp' . $event->end_time;
                                    }
                            }
                            else
                            {
                                    echo lang ('eventsmanager:on_date_label') . '&nbsp' . format_date ($event->start_date) . '&nbsp' .
                                    lang ('eventsmanager:at_time_label') . '&nbsp' . $event->start_time;
                            }
                            ?>
                    </p>
                    <h6><img src="<?php echo $module_path . '/img/house.png' ?>" />{{ helper:lang line="eventsmanager:place_label" }}</h6>
                    <p><?php echo $event->place; ?>&nbsp;
                        <?php if ($event->show_map): ?>
                        <a id="show_map" href="{{ url:current }}#event-map">
                        <?php echo lang ('eventsmanager:see_map_label'); ?>
                        </a>
                        <?php endif; ?>
                    </p>
                    <h6><img src="<?php echo $module_path . '/img/man.png' ?>" />{{ helper:lang line="eventsmanager:created_by_label" }}</h6>
                    <p><?php echo $event->author_display_name; ?></p>
                </td>
            </tr>
        </table>
    </div>
    <div id="event-details">
        <h6><img src="<?php echo $module_path . '/img/magnify.png' ?>" />{{ helper:lang line="eventsmanager:event_details_label" }}</h6>
        <p><?php echo $event->description; ?></p>
    </div>
    <?php if ($event->show_map): ?>
    <br style="clear: both;" />
    <div id="event-map">
        <h6><img src="<?php echo $module_path . '/img/gmaps.png' ?>" />{{ helper:lang line="eventsmanager:map_label" }}</h6>
        <div id="map_canvas"></div>
    </div>
    <?php endif; ?>
</div>
<br style="clear: both;" />
{{ widgets:instance id="3"}}
<!-- Comments -->
<?php if ($event->enable_comments): ?>
<div id="comments">
    <?php echo $this->load->view('partials/dialog-form');?>
    <div id="existing-comments">
        <h4><?php echo lang ('comments:title') ?></h4>
        <?php echo $this->comments->display () ?>
    </div>
    <!-- Only user who is following the event and logged in can comment -->
    <?php if(true || $this->events_lib->count_followers($event->slug, 'follow', $this->current_user->id)): ?>
    <?php echo $this->comments->form () ?>
    <?php endif; ?>
</div>
<?php endif; ?>
<script type="text/javascript">
    $('td#picture a').fancybox({
            type: 'inline',
            padding: 5,
            transitionIn: 'fade',
            transitionOut: 'fade',
            speedIn: 100,
            speedOut: 100
    });;
</script>
<!-- Maps Management -->
<?php if ($event->show_map): ?>
<!--<script type='text/javascript' src='http://maps.google.com/maps/api/js?sensor=false&language=<?php echo $this->current_user->lang; ?>'></script>-->
<script type='text/javascript'>
    $(document).ready(function()
    {
        function centerMap(lat, lng)
        {
                var latlng = new google.maps.LatLng(lat, lng);
                var map = new google.maps.Map(canvas.get(0),
                        {
                                zoom: 14,
                                center: latlng,
                                mapTypeId: google.maps.MapTypeId.ROADMAP,
                                mapTypeControl: true,
                                mapTypeControlOptions:
                                        {
                                                style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
                                        },
                                navigationControl: true,
                                navigationControlOptions:
                                        {
                                                style: google.maps.NavigationControlStyle.SMALL
                                        },
                                streetViewControl: true
                        });
    
                var marker = new google.maps.Marker(
                        {
                                map: map,
                                position: latlng
                        });
        }
    
        function geocodePlace(place)
        {
                geocoder.geocode(
                        {
                                address: place
                        }, function(results, status)
                {
                        if (status == google.maps.GeocoderStatus.OK)
                        {
                                latlng = results[0].geometry.location;
                                centerMap(latlng.lat(), latlng.lng());
                        }
                });
        }
    
        function refreshMap()
        {
    <?php if (isset ($event->pos_lat) && isset ($event->pos_lng)): ?>
                        // Automatic mode
                        var lat = "<?php echo $event->pos_lat; ?>";
                        var lng = "<?php echo $event->pos_lng; ?>";
                        centerMap(lat, lng);
    <?php else : ?>
                        // Latitude/Longitude mode
                        var place = "<?php echo $event->place; ?>";
                        geocodePlace(place);
    <?php endif; ?>
        }
    
        var canvas = $('div#map_canvas');
        canvas.css('width', '{{ settings:events_map_width }}');
        canvas.css('height', '{{ settings:events_map_height }}');
        var geocoder = new google.maps.Geocoder();
        refreshMap();
    });
</script>
<?php endif;
