<div class="comman-box">
    <span class="heading-comman"><?php echo $title; ?></span>(<?php echo $count; ?>)
    <ul class="modify-block clearfix">
        <?php foreach ($items as $item) : ?>
            <li>
                <span><?php echo $item->title; ?></span>
                <a href='/eventsmanager/about/<?php echo $item->slug; ?>' title='<?php echo $item->title; ?>'>
                    {{eventsmanager:thumb name='<?php echo $item->thumbnail; ?>'}}
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php if ($count > 6): ?>
        <span class="fr"><a href="#<?php echo $hashValue; ?>">View all</a></span>
    <?php endif; ?>
    <div class="clear"></div>
</div>