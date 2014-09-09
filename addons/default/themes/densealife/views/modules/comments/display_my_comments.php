<?php if ($comments): ?>  
    <ul class="status-blog">
        <?php foreach ($comments as $item): ?>
            <li class='li-<?php echo $item->id; ?>'>
                <?php echo load_view('comments', 'post', array('item' => $item, 'allowcomment' => $allowcomment)); ?>
            </li>
    <?php endforeach ?>
    </ul>
    <?php
 endif;