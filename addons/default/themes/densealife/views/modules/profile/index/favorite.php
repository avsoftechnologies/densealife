<div class="comman-heading">Favorite</div>    
<ul class="stream">
    <?php if(!empty($favorites)):?>
    <?php foreach($favorites as $favorite):?>
    <?php echo load_view('profile','/index/loop',array('data' => $favorite));?>
    <?php endforeach;?>
    <?php else:?>
    <li>No records Found</li>
    <?php endif;?>
</ul>
