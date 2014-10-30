<?php if ($comments): p($comments); ?>  
    <ul class="status-blog">
        <?php foreach ($comments as $item): ?>
            <li class='li-<?php echo $item->id; ?>'>
                
            </li>
    <?php endforeach ?>
    </ul>
    <?php
 endif;