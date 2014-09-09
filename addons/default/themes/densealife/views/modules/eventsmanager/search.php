
<?php foreach ( $events as $event ): ?>
    <?php echo load_view('profile', '/index/loop', array( 'data' => $event )); ?>
<?php endforeach; ?>
<li>
<span class="more">
    <a href="#streams/<?php echo $term;?>">See more suggestion</a>
</span>
</li>
