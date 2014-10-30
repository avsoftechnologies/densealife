<style>
    td.noti button.common {margin-top:0px;}
</style>
<div class="comman-heading">Notification</div>
<div class="clear"></div>
<?php if($notifications):?>
<ul class="notification mt15">
    <?php foreach($notifications as $notification):?>
    <?php if($notification->type=='comment'){
        $data = unserialize($notification->data);
    }?>
    <li class="<?php echo !empty($data['data']['comment_id']) ? 'pa_'. $data['data']['comment_id'] : '';?>">
        <?php if(!empty($notification->type)):?>
        <?php echo load_view('profile','notifications/partials/noti_'.$notification->type, array('data' => $notification));?>
        <?php endif;?>
    </li>
    <?php endforeach;?>
<li>
<?php else:?>
    <div class="clear"></div>
    <div class='txt-center mt15 fs15 color-blue'>You don't have any notification pending</div>    
<?php endif;?>
<div class="clear"></div>
