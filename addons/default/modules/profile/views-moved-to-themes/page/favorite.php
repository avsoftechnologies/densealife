<div class="comman-heading">Favorite</div>    
<ul class="stream">
    <?php if(!empty($favorites)):?>
    <?php foreach($favorites as $favorite):?>
    <?php echo $this->load->view('page/loop',array('data' => $favorite), true);?>
    <?php endforeach;?>
    <?php else:?>
    <li>No records Found</li>
    <?php endif;?>
</ul>
