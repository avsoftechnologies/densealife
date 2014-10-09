<script>
    $(function() {
        $("#datepicker_dob").datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2013',
            dateFormat: "yy-mm-dd"
        });
        
        $('#first_name, #last_name').addClass('namebg');
        $('#datepicker_dob').addClass('namebg').addClass(' dob-bg-position');
    });
    
</script>

<div class="events-heading">Join the Conversation</div>
<article>Connect and share the events you love.</article>
<div class="register-form">
        <?php if ( ! empty($error_string)):?>
<!-- Woops... -->
<div class="error-box">
	<?php echo $error_string;?>
</div>
<?php endif;?>

    <?php echo form_open('register', array('id' => 'register')) ?>
        <?php if ( ! Settings::get('auto_username')): ?>
		<label for="username"><?php echo lang('user:username') ?></label>
		<input type="text" name="username" maxlength="100" value="<?php echo $_user->username ?>" />
	<?php endif ?>
        <?php 
        $captcha = array();
        foreach($profile_fields as $field) : 
                if($field['required'] and $field['field_slug'] != 'display_name') :  ?>
                    <?php if($field['field_slug']=='captcha'):
                        $captcha = $field; 
                    else:?>
                   
		<label for="<?php echo $field['field_slug'] ?>"><?php echo (lang($field['field_name'])) ? lang($field['field_name']) : $field['field_name'];  ?></label>
		<div class="input"><?php echo $field['input'] ?></div>
	<?php   
        endif;
        endif; 
        endforeach;  ?>
        
        <!--<input type="text" name="dob" id="datepicker" value="" class="namebg dob-bg-position">-->
        <label for="email"><?php echo lang('global:email') ?></label>
        <input type="text" name="email" maxlength="100" value="<?php echo $_user->email ?>" class="namebg email-bg-position" />
        <?php echo form_input('d0ntf1llth1s1n', ' ', 'class="default-form" style="display:none"') ?>
        <label for="password"><?php echo lang('global:password') ?></label>
        <input type="password" name="password" maxlength="100" class="namebg password-bg-position" />
        
        <label for="<?php echo $captcha['field_slug'] ?>"><?php echo (lang($captcha['field_name'])) ? lang($captcha['field_name']) : $captcha['field_name'];  ?></label>
        <?php echo $captcha['input'] ?>
       
        <div class="button-cover">
            By clicking Sign Up, you agree to our {{ url:anchor segments="/terms-and-conditions" title="Terms and Condition" class="block"}}
            <button class="btn-color" id="submit-btn" type="submit">Sign Up</button>
            <button id="sign-btn">Reset</button></div>
    <?php echo form_close() ?>
</div>
<div class="event-slot-aera clearfix"></div>