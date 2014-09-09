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
			<?php echo form_open_multipart('', array('id'=>'user_edit', 'class' => 'crud_form')); ?>
			<div class="span10 offset1 form-horizontal well">
				<fieldset>
	        		<legend><?php echo ($this->current_user->id !== $_user->id) ?
					sprintf(lang('user_edit_title'), $_user->display_name) :
					lang('profile_edit') ?></legend>
	        		<br/>
	        		<ul class="nav nav-tabs">
				        <li class="active"><a href="#A" data-toggle="tab"><?php echo lang('user_details_section') ?></a></li>
				        <li><a href="#B" data-toggle="tab"><?php echo lang('user_email_label') ?></a></li>
				        <?php if ($api_key): ?>
				        <li><a href="#C" data-toggle="tab"><?php echo lang('profile_api_section') ?></a></li>
			      		<?php endif; ?>
			      	</ul>
			      	<div class="tabbable">
			        	<div class="tab-content">
			          		<div class="tab-pane active" id="A">
			          			<div class="control-group">
									<label class="control-label" for="display_name"><?php echo lang('profile_display_name'); ?></label>
						            <div class="controls">
						            	<?php echo form_input(array('name' => 'display_name', 'id' => 'display_name', 'value' => set_value('display_name', $display_name))); ?>
						            </div>
					          	</div>
								<?php foreach($profile_fields as $field): ?>
									<?php if($field['input']): ?>
									<div class="control-group">
										<label class="control-label" for="<?php echo $field['field_slug']; ?>"><?php echo (lang($field['field_name'])) ? lang($field['field_name']) : $field['field_name'];  ?><?php if ($field['required']) echo '<span>*</span>'; ?></label>
							            <div class="controls">
							            	<?php echo $field['input']; ?>
							            	<p class="help-block"><?php if($field['instructions']) echo '<p class="instructions">'.$field['instructions'].'</p>'; ?></p>
							            </div>
						          	</div>		
									<?php endif; ?>
								<?php endforeach; ?>
			          		</div>
			          		<div class="tab-pane" id="B">
			            		
			            		<div class="control-group">
									<label class="control-label" for="email"><?php echo lang('user_email') ?></label>
			            			<div class="controls">
										<?php echo form_input('email', $_user->email); ?>
									</div>
		          				</div>
		          				<div class="control-group">
									<label class="control-label" for="password"><?php echo lang('user_password') ?></label>
			            			<div class="controls">
			            				<?php echo form_password('password', '', 'autocomplete="off"'); ?>
			            			</div>
		          				</div>

			          		</div>
			          		<?php if ($api_key): ?>
		          			<?php if (Settings::get('api_enabled') and Settings::get('api_user_keys')): ?>
							<script>
							jQuery(function($) {	
								$('input#generate_api_key').click(function(){
										
									var url = "<?php echo site_url('api/ajax/generate_key') ?>",
										$button = $(this);
										
									$.post(url, function(data) {
										$button.prop('disabled', true);
										$('span#api_key').text(data.api_key).parent('li').show();
									}, 'json');
								});
							});
							</script>
							<?php endif; ?>
			          		<div class="tab-pane" id="C">
			        			<div class="control-group">
									<label class="control-label" for="email"><?php echo lang('user_email') ?></label>
						            <div class="controls">
						            	<?php $api_key or print('style="display:none"') ?>><?php echo sprintf(lang('api:key_message'), '<span id="api_key">'.$api_key.'</span>'); ?>
						            </div>
					          	</div>
					          	<div class="control-group">
									<label class="control-label" for="email"><?php echo lang('user_email') ?></label>
						            <div class="controls">
						            	<input type="button" id="generate_api_key" value="<?php echo lang('api:generate_key') ?>" />
						            </div>
					          	</div>
			        		</div>
			        		<?php endif; ?>
			        	</div>
			      	</div> <!-- /tabbable -->
		          	<div class="form-actions">
	            		<?php echo form_submit('btnSubmit', lang('profile_save_btn'), 'class="btn btn-primary"'); ?>
	          		</div>
	        	</fieldset>
			</div>
		<?php echo form_close(); ?>
		</div>
	</div>
</section>