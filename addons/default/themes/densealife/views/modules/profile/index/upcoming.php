<div class="comman-heading">Upcoming</div>    
<ul class="stream">
   <?php if(!empty($upcomings)):?>
    <?php foreach($upcomings as $upcoming):?>
    <?php echo load_view('profile','/index/loop',array('data' => $upcoming));?>
    <?php endforeach;?>
    <?php else:?>
    <li>No records found</li>
    <?php endif; ?>
</ul>
