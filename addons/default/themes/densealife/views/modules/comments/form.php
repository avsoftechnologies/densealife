<?php echo form_open("comments/create/{$module}", 'class="form-status"'); ?>
<noscript><?php echo form_input('d0ntf1llth1s1n', '', 'style="display:none"'); ?></noscript>
<?php echo form_hidden('entry', $entry_hash); ?>
<?php echo form_hidden('parent_id', $parent_id); ?>
<?php if($parent_id==0):?>
<div id="response">&nbsp;</div>
<?php endif;?>
<textarea cols="" rows="" name="comment" class="main-comment<?php if($parent_id!=0) : echo " form-post-comment ";  endif; ?>" ><?php echo $comment['comment']; ?></textarea>
<?php if(is_null($parent_id)) : ?>
<div class="status-bg"><button class="btn-color post-btn" type="submit" onclick="if($(this).parent().siblings('textarea').val()=='') return false;">Post</button></div>
<?php endif; ?>
<?php echo form_close();?>