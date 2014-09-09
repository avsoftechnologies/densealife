<div class="comman-heading">Notification</div>
<div class="clear"></div>
<ul class="notification mt15">
    <?php foreach($notifications as $notification) :?>
    <li>
            <?php echo load_view('profile','notifications/partials/noti_'.$notification->type,array('data' => $notification));?>
    </li>
    <?php endforeach;?>
<li>
<div class="clear"></div>
