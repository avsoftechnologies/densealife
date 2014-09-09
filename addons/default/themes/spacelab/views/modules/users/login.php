<section id="login">
	<div class="row-fluid">
		<?php if (validation_errors()): ?>
		<!-- Woops... -->
		<div class="row-fluid">
		    <div class="span12">
		      <div class="alert alert-error">
		        <a class="close">&times;</a>
		        <?php echo validation_errors();?>
		      </div>
		    </div>
		</div>
		<?php endif; ?>
		<div class="row-fluid">
		<?php echo form_open('users/login', array('id'=>'login', 'class' => 'crud_form'), array('redirect_to' => $redirect_to)); ?>
			<div class="span10 offset1 form-horizontal well">
				<fieldset>
	        		<legend><?php echo lang('user_login_header') ?></legend>

	        		<div class="control-group">
						<label class="control-label" for="email"><?php echo lang('user_email') ?></label>
			            <div class="controls">
			            	<?php echo form_input('email', $this->input->post('email') ? $this->input->post('email') : '')?>
			            </div>
		          	</div>

	        		<div class="control-group">
						<label class="control-label" for="password"><?php echo lang('user_password') ?></label>
			            <div class="controls">
			            	<input type="password" id="password" name="password" maxlength="20" />
			            </div>
		          	</div>

		          	<div class="control-group">
						<label class="control-label" for="remember"><?php echo lang('user_remember'); ?></label>
			            <div class="controls">
			            	<?php echo form_checkbox('remember', '1', FALSE); ?>
			            </div>
		          	</div>

		          	<div class="form-actions">
	            		<?php echo form_submit('btnSubmit', lang('user_login_btn'), 'class="btn btn-primary"'); ?>
	            		<button type="reset" class="btn"><?php echo anchor('register', lang('user_register_btn')); ?></button>
	            		<button type="reset" class="btn"><?php echo anchor('users/reset_pass', lang('user_reset_password_link'));?></button>
	          		</div>

				</fieldset>
			</div>
		<?php echo form_close(); ?>
		</div>
	</div>
</section>