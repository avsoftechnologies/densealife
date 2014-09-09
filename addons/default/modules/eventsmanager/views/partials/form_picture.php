<input type="hidden" id="thumbnail" name="thumbnail" value="<?php echo isset($event->thumbnail) ? $event->thumbnail : null ; ?>" />
<input type="hidden" id="thumbnail_x1" name="thumbnail_x1" value="<?php echo isset($event->thumbnail_x1) ? $event->thumbnail_x1 : null ; ?>" />
<input type="hidden" id="thumbnail_y1" name="thumbnail_y1" value="<?php echo isset($event->thumbnail_y1) ? $event->thumbnail_y1 : null ; ?>" />
<input type="hidden" id="thumbnail_x2" name="thumbnail_x2" value="<?php echo isset($event->thumbnail_x2) ? $event->thumbnail_x2 : null ; ?>" />
<input type="hidden" id="thumbnail_y2" name="thumbnail_y2" value="<?php echo isset($event->thumbnail_y2) ? $event->thumbnail_y2 : null ; ?>" />
<input type="hidden" id="thumbnail_disp_w" name="thumbnail_disp_w" value="100" />
<input type="hidden" id="thumbnail_disp_h" name="thumbnail_disp_h" value="100" />

<!-- Step 1 : choose a picture -->

<div id="col_1">
	<h2><?php echo lang("eventsmanager:picture_step_1"); ?></h2>
	<div id="choosing">
		<!-- Folder selector -->
		<?php
			if(!empty($folders)) :
				$folder_id = isset($picture->folder_id) ? $picture->folder_id : null;
				echo lang('eventsmanager:choose_folder_label') . '&nbsp;:&nbsp;' . form_dropdown('folder_select', $folders, $folder_id);
			else :
				echo lang('eventsmanager:no_folder_label');
			endif;
		?>
		<br /><br />
		<!-- Folder content -->
		<input type="hidden" id="picture_id" value="<?php echo isset($event->picture_id) ? $event->picture_id : null ; ?>" />
		<div id="message">
			<?php if(empty($pictures)) : ?>
				<p>Il n'y a aucune image dans ce dossier.</p>
			<?php endif; ?>
		</div>
		<ul id="folder-content">
		<?php
		if(!empty($pictures)) :
			foreach($pictures as $pic) {
				echo '<li>'
				. img(array('src' => 'files/thumb/'.$pic->id, 'alt' => $pic->name, 'title' => $pic->name . ' -- ' . $pic->description, 'filename' => $pic->filename))
				. form_radio(array(	'class' => 'picture_id',
									'name' => 'picture_id',
									'value' => $pic->id,
									'filename' => $pic->filename,
									'data-width' => $pic->width,
									'data-height' => $pic->height,
									'checked' => isset($event->picture_id) && $event->picture_id == $pic->id ? true : false))
				. '</li>';
			}
		endif; ?>
		</ul>
	</div>
</div>


<div id="col_2">
	<h2><?php echo lang("eventsmanager:picture_step_2"); ?></h2>
	<div id="cropping">
		<div id="raw-wrapper">
			<?php if(isset($picture)) :
				echo img(array('src' => UPLOAD_PATH.'files/'.$picture->filename, 'id' => 'selected-picture', 'data-width' => $picture->width, 'data-height' => $picture->height));
			else :
				echo img(array('src' => $module_path . '/img/event.png', 'id' => 'selected-picture', 'data-width' => 128, 'data-height' => 128));
			endif; ?>
		</div>
		<br />
		<div id="preview-wrapper">
			<div id="preview">
				<?php if(isset($picture)) : echo img(array('src' => UPLOAD_PATH.'files/'. $picture->filename, 'id' => 'cropped-picture-preview'));
				else : echo img(array('src' => $module_path . '/img/event.png', 'id' => 'cropped-picture-preview'));
				endif; ?>
			</div>
			<span><?php echo lang('eventsmanager:preview_label'); ?></span>
			<img src="<?php echo $module_path . '/img/preview.png'; ?>" />
		</div>
		<div id="preview-coords">
			<h3><?php echo lang("eventsmanager:selection_coords"); ?></h3>
			<label for="prev-coord-x1"><?php echo lang("eventsmanager:upper_left_corner"); ?> :</label>
			<span>x1&nbsp;</span><input type="text" id="prev-coord-x1" disabled />
			<span>&nbsp;y1&nbsp;</span><input type="text" id="prev-coord-y1" disabled />
			<br />
			<label for="prev-coord-x2"><?php echo lang("eventsmanager:lower_right_corner"); ?> :</label>
			<span>x2&nbsp;</span><input type="text" id="prev-coord-x2" disabled />
			<span>&nbsp;y2&nbsp;</span><input type="text" id="prev-coord-y2" disabled />
		</div>
	</div>
</div>

<br class="clear-both" />
