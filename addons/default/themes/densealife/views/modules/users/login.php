<h2 class="page-title" id="page_title"><?php echo lang('user:login_header') ?></h2>

<?php if ( validation_errors() ): ?>
    <div class="error-box">
        <?php echo validation_errors() ; ?>
    </div>
<?php endif ?>

<?php echo form_open('users/login', array( 'id' => 'login' ), array( 'redirect_to' => $redirect_to )) ?>
<ul>
    <li>
        <label for="email"><?php echo lang('global:email') ?></label>
        <?php echo form_input('email', $this->input->post('email') ? $this->input->post('email') : '') ?>
    </li>
    <li>
        <label for="password"><?php echo lang('global:password') ?></label>
        <input type="password" id="password" name="password" maxlength="20" />
    </li>
    <li id="remember_me">
        <input type="checkbox" id="remember-checksidebar" name="remember" value="1"  />
         <span>{{ helper:lang line="user:remember" }}</span> | <span><a href="{{ url:site uri='users/reset_pass' }}" title="{{ helper:lang line='user:reset_password_link' }}">{{ helper:lang line="user:reset_password_link" }}</a></span>
    </li>
    <li class="form_buttons">
        <div class="button-cover">
       <button class="btn-color" type="submit" name="btnLogin">{{ helper:lang line='user:login_btn' }}</button>
        <button type="button" onClick="javascript:window.location.href='{{url:site uri='users/register'}}'">{{ helper:lang line='user:register_btn' }}</button>
        </div>
    </li>
</ul>
<?php echo form_close() ?>