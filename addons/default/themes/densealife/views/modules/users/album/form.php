<?php
// Change the css classes to suit your needs    

$attributes = array('class' => '', 'id' => 'form-add-album');
echo form_open('album/create', $attributes);
?>
<input type="hidden" name="event_id" value="<?php echo $event_id; ?>"/>
<p>
<label for="album_name">Album Name <span class="required">*</span></label>
<?php echo form_error('album_name'); ?>
<br /><input id="album_name" type="text" name="album_name" maxlength="100" value="<?php echo set_value('album_name'); ?>"  />
</p>
<p>
<label for="privacy">Privacy <span class="required">*</span></label>
<?php echo form_error('privacy'); ?>

<?php // Change the values in this array to populate your dropdown as required ?>
<?php
$options    = array(
    ''          => 'Please Select',
    'public'    => 'Viewable by all',
    'protected' => 'Viewable by friends',
    'private'   => 'Viewable by me',
);
?>

<br /><?php echo form_dropdown('privacy', $options, set_value('privacy')) ?>
</p>                                             


<p class="align-center">
<?php echo form_button(array('type' => 'submit'), 'Add album', 'class="btn-color"'); ?>
</p>

<?php
echo form_close();
