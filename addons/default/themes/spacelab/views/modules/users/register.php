<section id="register">
	<div class="row-fluid">
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
		<div class="row-fluid">
		<?php echo form_open('register', array('id' => 'register', 'class' => 'crud_form')); ?>
			<div class="span10 offset1 form-horizontal well">
				<fieldset>
	        		<legend><?php echo lang('user_register_header') ?></legend>
	        		<ul class="nav nav-pills">
						<li class="active"><a href="#"><?php echo lang('user_register_step1') ?></a></li>
						<li><a href="#">-&gt;</a></li>
						<li><a href="#"><?php echo lang('user_register_step2') ?></a></li>
					</ul>
					<?php if ( ! Settings::get('auto_username')): ?>
					<div class="control-group">
						<label class="control-label" for="username"><?php echo lang('user_username') ?></label>
				        <div class="controls">
				            <input type="text" name="username" maxlength="100" value="<?php echo $_user->username; ?>" />
				        </div>
			        </div>
					<?php endif; ?>
					<div class="control-group">
						<label class="control-label" for="email"><?php echo lang('user_email') ?></label>
				        <div class="controls">
				           <input type="text" name="email" maxlength="100" value="<?php echo $_user->email; ?>" />
							<?php echo form_input('d0ntf1llth1s1n', ' ', 'class="default-form" style="display:none"'); ?>
				        </div>
			        </div>			
					<div class="control-group">
						<label class="control-label" for="password"><?php echo lang('user_password') ?></label>
			            <div class="controls">
			              <input type="password" name="password" maxlength="100" />
			            </div>
		          	</div>
					<?php foreach($profile_fields as $field):
						if ($field['required'] and $field['field_slug'] != 'display_name'): ?>
							<div class="control-group">
								<label class="control-label" for="<?php echo $field['field_slug']; ?>"><?php echo (lang($field['field_name'])) ? lang($field['field_name']) : $field['field_name'];  ?></label>
					            <div class="controls">
					              <?php echo $field['input']; ?>
					            </div>
				          	</div>
					<?php endif;
						endforeach; ?>
					<div class="form-actions">
	            		<?php echo form_submit('btnSubmit', lang('user_register_btn'), 'class="btn btn-primary"') ?>
	            		<button type="reset" class="btn">{{ helper:lang line="cancel_label" }}</button>
	          		</div>
				</fieldset>
			</div>
		<?php echo form_close(); ?>
		</div>
	</div>
</section>