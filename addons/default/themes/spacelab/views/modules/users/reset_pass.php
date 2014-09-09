<section id="login">
	<div class="row-fluid">
		<h2><?php echo lang('user_reset_password_title');?></h2>
		<?php if (!empty($error_string)): ?>
		<!-- Woops... -->
		<div class="row-fluid">
		    <div class="span12">
		      <div class="alert alert-error">
		        <a class="close">&times;</a>
		        <?php echo $error_string;?>
		      </div>
		    </div>
		</div>
		<?php endif; ?>
		<?php if (!empty($success_string)): ?>
		<div class="row-fluid">
		    <div class="span12">
		      <div class="alert alert-success">
		        <a class="close">&times;</a>
		        <?php echo $success_string; ?>
		      </div>
		    </div>
		</div>
		<?php endif; ?>
		<div class="row-fluid">
			<?php echo form_open('users/reset_pass', array('id'=>'reset-pass', 'class' => 'crud_form')); ?>
			<div class="span10 offset1 form-horizontal well">
				<fieldset>
	        		<legend><?php echo lang('user_reset_instructions'); ?></legend>
	        		<div class="control-group">
						<label class="control-label" for="email"><?php echo lang('user_email') ?></label>
					    <div class="controls">
					      	<input type="text" name="email" maxlength="100" value="<?php echo set_value('email'); ?>" />
					    </div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username"><?php echo lang('user_username') ?></label>
					    <div class="controls">
					      	<input type="text" name="user_name" maxlength="40" value="<?php echo set_value('user_name'); ?>" />
					    </div>
					</div>
	        		<div class="form-actions">
	            		<?php echo form_submit('btnSubmit', lang('user_reset_pass_btn'), 'class="btn btn-primary"') ?>
	          		</div>
	        	</fieldset>
			</div>
		<?php echo form_close(); ?>
		</div>
	</div>
</section>