
<div class="comman-heading">
    <div class="d-block">
        <span>All <?php echo $type; ?></span>
        <button class="fr events-btn margin-zero btn-color" 
                onClick="window.location.href = '/densealife-page/<?php if ($type == 'interest'): ?>create_interest <?php else: ?>create_event<?php endif; ?>'">
            + <?php if ($type == 'interest'): ?>Create Interest<?php else: ?> Create Event <?php endif; ?>
        </button>
    </div>
</div>
<div class="right events-btn" style="margin-right: -7px;">
    <span class="txt-up fl">Filter:</span>
    <form method="post" action="" id="frm-sub-cat-filter">
        <input type="hidden" name="type" value="<?php echo $type; ?>" id="entry_type"/>
        <?php echo form_dropdown('sub_category_id', array('' => 'All') + $sub_categories, '', 'class="drpdwn_sub_category_id"') ?>
    </form>
</div>
</div>
<ul class="stream">
    <h2 class="heading">Trending</h2>
    <?php if (!empty($trendings)): ?>
        <?php foreach ($trendings as $trending): ?>
            <?php echo load_view('profile', '/index/loop', array('data' => $trending)); ?>
        <?php endforeach; ?>
    <?php else: ?>
        <li>No records found</li>
    <?php endif; ?>

    <h2 class="heading">Favorites</h2>
    <?php if (!empty($favorites)): ?>
        <?php foreach ($favorites as $favorite): ?>
            <?php echo load_view('profile', '/index/loop', array('data' => $favorite)); ?>
        <?php endforeach; ?>
    <?php else: ?>
        <li>No records Found</li>
    <?php endif; ?>

    <?php if ($type != 'interest'): ?>
        <h2 class="heading">Upcoming</h2>
        <?php if (!empty($upcomings)): ?>
            <?php foreach ($upcomings as $upcoming): ?>
                <?php echo load_view('profile', '/index/loop', array('data' => $upcoming)); ?>
            <?php endforeach; ?>
        <?php else: ?>
            <li>No records found</li>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($type != 'event'): ?>
        <h2 class="heading">Popular</h2>
        <?php if (!empty($populars)): ?>
            <?php foreach ($populars as $popular): ?>
                <?php echo load_view('profile', '/index/loop', array('data' => $popular)); ?>
            <?php endforeach; ?>
        <?php else: ?>
            <li>No records found</li>
        <?php endif; ?>
    <?php endif; ?>
</ul>
