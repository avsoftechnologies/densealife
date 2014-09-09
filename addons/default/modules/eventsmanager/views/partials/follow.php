<div style="float:right;">
    <span style="float:left;">{{ eventsmanager:followers type='follow' slug='<?php echo $slug;?>' }}<?php echo anchor(is_logged_in() ? 'eventsmanager/'.$slug.'/follower' : '/users/login', 'Follow', array('style'=>'float:left;clear:right'));?></span>
    <span style="float:left; margin-left:5px;">{{ eventsmanager:followers type='fav' slug='<?php echo $slug;?>' }}<?php echo anchor(is_logged_in() ? 'eventsmanager/'.$slug.'/favourite' :'/users/login','Favourite',  array('style'=>'float:left;'));?></span>
    <span style="float:left; margin-left: 5px;">{{ eventsmanager:followers type='star' slug='<?php echo $slug;?>' }}<?php echo anchor(is_logged_in() ? 'eventsmanager/'.$slug.'/starred' : '/users/login', 'Stared',  array('style'=>'float:left;'));?></span>
</div>



