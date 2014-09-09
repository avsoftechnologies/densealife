<div class="comman-box clearfix">
    <div class="comman-heading">Find Friends</div>
    <div class="clear"></div>
    <div class="comman-box clearfix">
        <div class="heading">Search</div>
        <?php 
        $attributes = array('class' => '', 'id' => 'find-friend');
        echo form_open('/profile/friends/find', $attributes); ?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td>
                    <label for="name">Name</label>
                    <?php echo form_error('name'); ?>
                    <br />
                    <input id="name" type="text" name="name"  value="<?php echo set_value('name'); ?>"  />
                </td>
                <td>
                    <label for="email">Email</label>
                    <?php echo form_error('email'); ?>
                    <br /><input id="email" type="text" name="email"  value="<?php echo set_value('email'); ?>"  />
                </td>
            </tr>
            <tr>
                <td>
                   <label for="location">Location</label>
                    <?php echo form_error('location'); ?>
                    <br /><input id="location" type="text" name="location"  value="<?php echo set_value('location'); ?>"  />
                </td>
                <td><label for="Gender">Gender</label>
                <input type="text" name="gender" id="Gender" value="<?php echo set_value('gender'); ?>" class="Gender"></td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td><button class="common right">Search</button></td>
            </tr>
        </table>
        <?php echo form_close(); ?>
    </div>
    <div class="comman-box clearfix">
        <div class="heading">Invite Friends</div>
        <?php echo $this->session->flashdata('email');?>
        <?php echo form_open('/profile/friends/invite', array('id' => 'invite-friend', 'onSubmit' => 'return false;')); ?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
            <td>
            <label for="email">Email</label>
                    <?php echo form_error('email'); ?>
                    <br /><input id="invite-email" type="text" name="email"  value="<?php echo set_value('email'); ?>"  />
            </td>
            <td><label for="InviteFriends"> &nbsp;</label><button class="common right">Invite Friends</button></td>
            </tr>
            <tr>
            <td>
                <!---<div id="fb-root"></div>--> 
               
                <script>
                    /*FB.init({ 
                      //appId:'647735295292665', cookie:true,
						appId:'1451997905084711',
						//appId:'257299417811358',
						cookie:true,
						status:true,
						xfbml:true 
                    });
					
                    function FacebookInviteFriends()
                    {
                        FB.ui({
							method: 'apprequests', 
							message: 'My diaolog...'
						});
					}*/
                </script>
                <!--<button class="btn-color common" onclick="FacebookInviteFriends(); return false;">Invite Facebook Friends</button>-->
				<button class="btn-color common" onclick="FBInvite();">Invite Facebook Friends</button>
            </td>
            <td>&nbsp;</td>
            </tr>
        </table>
        <?php echo form_close(); ?>
    </div>
    <?php if(!empty($result)) :?>
    <div class="comman-box clearfix">
        <div class="heading">Make New Friends</div>
        <ul class="followers">
            <?php foreach($result as $suggestion):?>
            <li>
            <span>{{user:profile_pic user_id='<?php echo $suggestion->user_id;?>'}}</span> 
            <?php  add_friend_button($suggestion->user_id,$_user->user_id);?>
            <span class="name"><?php echo $suggestion->display_name;?></span> 
            <span class="name">Location: <?php echo $suggestion->address_line1.' '. $suggestion->address_line2.' '. $suggestion->address_line3;?></span> 
            </li>
            <?php endforeach;?>
        </ul>
    </div>
    <?php endif;?>
	<!--- facebook invite -->
	
	<div id="facebook_invite"></div>
	<script src="http://connect.facebook.net/en_US/all.js"></script>
	
	
	<script>
	 FB.init({
	  appId:'228443877332940',
	  cookie:true,
	  status:true,
	  xfbml:true
	 });
	</script>
	<script>
	 function FBInvite(){
	  FB.ui({
	   method: 'apprequests',
	   message: 'Invite your Facebook Friends'
	  },function(response) {
	   if (response) {
		//alert('Successfully Invited');
	   } else {
		//alert('Failed To Invite');
	   }
	  });
	 }
	</script>
	<!--- end facebook invite -->
</div>
<script type='text/javascript'>
if (top.location!= self.location)
{
top.location = self.location
}
</script>