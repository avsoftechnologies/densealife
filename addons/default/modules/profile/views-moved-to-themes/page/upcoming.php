<div class="comman-heading">Upcoming</div>    
<ul class="stream">
   <?php if(!empty($upcomings)):?>
    <?php foreach($upcomings as $upcoming):?>
    <?php echo $this->load->view('page/loop',array('data' => $upcoming), true);?>
    <?php endforeach;?>
    <?php else:?>
    <li>No records found</li>
    <?php endif; ?>
</ul>
