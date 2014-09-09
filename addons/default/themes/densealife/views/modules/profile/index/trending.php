<div class="comman-heading">Trending</div>    
<ul class="stream">
    <?php if ( !empty($trendings) ): ?>
        <?php foreach ( $trendings as $trending ): ?>
           <?php echo load_view('profile','/index/loop',array('data' => $trending));?>
        <?php endforeach; ?>
    <?php else: ?>
        <li>No records found</li>
    <?php endif; ?>
</ul>

<script>
    var ENTRY_TYPE = '<?php echo $type;?>';
</script>