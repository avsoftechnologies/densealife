<div class="comman-heading">Trending</div>    
<ul class="stream">
    <?php if ( !empty($trendings) ): ?>
        <?php foreach ( $trendings as $trending ): ?>
           <?php echo $this->load->view('page/loop',array('data' => $trending), true);?>
        <?php endforeach; ?>
    <?php else: ?>
        <li>No records found</li>
    <?php endif; ?>
</ul>
