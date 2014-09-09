<script>
    $(function() {
        $("#tabs").tabs();
    });
   
    $('#user_edit').ajaxForm({
    type: 'POST',
//    delegation: true, // for live response
    dataType: 'json',
    beforeSubmit: function() {
        $.fancybox.showLoading();
    },
    complete: function() {
        $.fancybox.hideLoading();
        $.fancybox.close();
        window.location.reload();
    }
});
    
</script>
<?php 
$profile = array('profile_pic','first_name', 'last_name');
$bio= array('dob','gender', 'lang', 'bio');
$address = array('phone', 'mobile', 'address_line1', 'address_line2', 'address_line3', 'postcode');
$profileFields = array();
foreach($profile_fields as $pf):
    if(in_array($pf['field_slug'],$profile)):
        $profileFields[] = $pf;
    endif;
    
    if(in_array($pf['field_slug'],$bio)):
        $bioFields[] = $pf;
    endif;
    
    if(in_array($pf['field_slug'],$address)):
        $addressFields[] = $pf;
    endif;
    
endforeach;
?>
<?php echo form_open_multipart('', array( 'id' => 'user_edit' )); ?>
<div id="tabs" style="width:700px;">
    <ul>
        <li><a href="#profile">Profile</a></li>
        <li><a href="#bio">Bio</a></li>
        <li><a href="#address">Address</a></li>
    </ul>
    <div id="profile">
        <ul>
            <li>
                <label for="display_name"><?php echo lang('profile_display_name') ?></label>
                <div class="input">
                    <?php echo form_input(array( 'name' => 'display_name', 'id' => 'display_name', 'value' => set_value('display_name', $display_name) )) ?>
                </div>
            </li>
            
             <?php foreach ($profileFields as $field ): ?>
                <?php if ( $field['input'] && $field['field_name'] != 'Captcha' ): ?>
                    <li>
                        <label for="<?php echo $field['field_slug'] ?>">
                            <?php echo (lang($field['field_name'])) ? lang($field['field_name']) : $field['field_name']; ?>
                            <?php if ( $field['required'] ) echo '<span>*</span>' ?>
                        </label>
                        <?php if ( $field['instructions'] ) echo '<p class="instructions">' . $field['instructions'] . '</p>' ?>
                        <div class="input">
                            <?php echo $field['input'] ?>
                        </div>
                    </li>
                <?php endif ?>
            <?php endforeach ?>
            <li>
                <label for="email"><?php echo lang('global:email') ?></label>
                <div class="input">
                    <?php echo form_input('email', $_user->email) ?>
                </div>
            </li>

            <li>
                <label for="password"><?php echo lang('global:password') ?></label><br/>
                <?php echo form_password('password', '', 'autocomplete="off"') ?>
            </li>

        </ul>
    </div>

    <div id="bio">
        <ul>
            <?php foreach ($bioFields as $field ): ?>
                <?php if ( $field['input'] && $field['field_name'] != 'Captcha' ): ?>
                    <li>
                        <label for="<?php echo $field['field_slug'] ?>">
                            <?php echo (lang($field['field_name'])) ? lang($field['field_name']) : $field['field_name']; ?>
                            <?php if ( $field['required'] ) echo '<span>*</span>' ?>
                        </label>
                        <?php if ( $field['instructions'] ) echo '<p class="instructions">' . $field['instructions'] . '</p>' ?>
                        <div class="input">
                            <?php echo $field['input'] ?>
                        </div>
                    </li>
                <?php endif ?>
            <?php endforeach ?>
        </ul>
    </div>
    
    <div id="address">
         <ul>
            <?php foreach ($addressFields as $field ): ?>
                <?php if ( $field['input'] && $field['field_name'] != 'Captcha' ): ?>
                    <li>
                        <label for="<?php echo $field['field_slug'] ?>">
                            <?php echo (lang($field['field_name'])) ? lang($field['field_name']) : $field['field_name']; ?>
                            <?php if ( $field['required'] ) echo '<span>*</span>' ?>
                        </label>
                        <?php if ( $field['instructions'] ) echo '<p class="instructions">' . $field['instructions'] . '</p>' ?>
                        <div class="input">
                            <?php echo $field['input'] ?>
                        </div>
                    </li>
                <?php endif ?>
            <?php endforeach ?>
        </ul>
    </div>
</div>
<?php echo form_submit('', lang('profile_save_btn')) ?>
<?php echo form_close() ?>