<script>
    var RECENTLY_CREATED_EVENT_ID = '<?php echo $this->session->userdata('recently_created_event');?>';
</script>
 
<?php $module_path = BASE_URL . $this->module_details['path']; ?>
<?php if ($this->method == 'create'): ?>
<div class="comman-heading">Create an <?php echo ucfirst($type);?></div>
<?php else: ?>
<div class="comman-heading"><?php echo lang('eventsmanager:manage_event_label') . ' : ' . $event->title; ?></div>

<?php endif; ?>
<div class="<?php echo $this->session->userdata('recently_created_event')!='' ? 'd-block' : 'd-none' ; ?> view-event">
    <a target="__blank" href="/eventsmanager/{{event:slug}}" id="event-url" class="fr mt10 f-bold color-blue">View Event >></a>
</div>
<div class="clear hgt28"></div>
<div id="tabs">
    <ul>
        <li><a href="#tabs-1">Content</a></li>
        <li><a id="tab-album" href="/densealife-page/album">Album</a></li>
        <li><a id="tab-thumbnail" href="/densealife-page/thumbnail">Thumbnail and Cover Photo</a></li>
    </ul>
    <div id="tabs-1">
        <div class='error-tab-1 d-none'></div>
        <?php echo load_view('eventsmanager','partials/form_content', array('event' => $event, 'type' => $type)); ?>
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