<?php $module_path = BASE_URL . $this->module_details['path'];  ?>
<?php if ($this->method == 'create'): ?>
<div class="comman-heading"><?php echo lang('eventsmanager:new_event_label'); ?></div>
<?php else: ?>
<div class="comman-heading"><?php echo lang('eventsmanager:manage_event_label') . ' : ' . $event->title; ?></div>

<?php endif; ?>

<?php $this->load->view('partials/form_notices'); ?>

<?php echo form_open_multipart(); ?>

<h3><?php echo lang('eventsmanager:content_label'); ?></h3>
<fieldset>
  <?php echo load_view('eventsmanager','partials/form_content', array('event' => $event)); ?>
</fieldset>

<h3><?php echo lang('eventsmanager:thumbnail_label'); ?></h3>
<fieldset>
  <?php $this->load->view('partials/form_picture', array('module_path' => $module_path)); ?>
</fieldset>

<h3><?php echo lang('eventsmanager:map_label'); ?></h3>
<fieldset>
  <?php $this->load->view('partials/form_map'); ?>
</fieldset>

<hr>

<button type="submit" name="btn-action" value="save" class="btn btn-primary btn-classy-primary"><?php echo lang('buttons:save') ?></button>
<button type="submit" name="btn-action" value="save_exit" class="btn btn-primary btn-classy-primary"><?php echo lang('buttons:save_exit') ?></button>
<?php
  if($this->method == 'create'):
    echo anchor($this->module_details['slug'], lang('buttons:cancel'), 'class="btn"');
  elseif($this->method == 'edit'):
    echo anchor($this->module_details['slug'].'/'.$event->slug, lang('buttons:cancel'), 'class="btn"');
  endif;
?>

<?php echo form_close(); ?>


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
