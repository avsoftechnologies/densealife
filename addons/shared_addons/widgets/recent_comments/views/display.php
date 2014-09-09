<ul class="recent-comments">
	<?php foreach($blog_widget as $post_widget): ?>
		<li><?php echo $post_widget->item; ?> <em>(<?php echo $post_widget->name; ?>)</em></li>
	<?php endforeach; ?>
</ul>