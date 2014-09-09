<script>
    $(function() {
        $("#tabs").tabs({
            beforeLoad: function(event, ui) {
                ui.jqXHR.error(function() {
                    ui.panel.html(
                            "Couldn't load this tab. We'll try to fix this as soon as possible. " +
                            "If this wouldn't be a demo.");
                });
            }
        });
        
        $("#start_date").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            onClose: function(selectedDate) {
                $("#end_date").datepicker("option", "minDate", selectedDate);
            }
        });
        $("#end_date").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            onClose: function(selectedDate) {
                $("#start_date").datepicker("option", "maxDate", selectedDate);
            }
        });
    });
</script>
<div class="comman-heading clear"><?php echo $title;?></div>
<div class="clear hgt28"></div>
<div id="tabs">
    <ul>
        <li><a href="#tabs-1">Content</a></li>
        <li><a href="/densealife-page/thumbnail">Thumbnail</a></li>
        <li><a href="/densealife-page/coverphoto">Coverphoto</a></li>
        <li><a href="/densealife-page/album">Album</a></li>
    </ul>
    <div id="tabs-1">
        <?php echo load_view('profile','index/partials/form_content', array('event' => $event)); ?>
    </div>
</div>


<script type="text/javascript">
  var SITE_URL = '<?php echo site_url(); ?>';
  // PARAMS
  var CURRENT_LANGUAGE = '<?php echo CURRENT_LANGUAGE; ?>';
  var JS_DATE_FORMAT = '<?php echo addslashes(php2js_date_format($date_format)); ?>';
  var DATE_INTERVAL = <?php echo $date_interval; ?>;
  // LANG
  var LANG_NO_IMAGE = '<?php echo lang('eventsmanager:no_image_in_folder'); ?>';
  var LANG_AJAX_ERROR = '<?php echo lang('eventsmanager:ajax_error'); ?>';
  var MAP_PLACE = '<?php echo isset($event->place) ? $event->place : ""; ?>';
</script>