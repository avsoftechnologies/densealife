<div class="comman-heading">Activity</div>
<!--<ul class="status">
    <li>
        <span>{{ theme:image file="status.png" alt="Status" }}</span>
        <a href="javascript:void(0);" class="wall-status" data-type="text">Post Status</a>
    </li>
    <li>
        <span>{{ theme:image file="addvidoes.png" alt="Videos" }}</span>
        <a href="javascript:void(0);" class="wall-status" data-type="image-video" data-event='0' data-title =''>Add Photos/Video</a>
    </li>
    
</ul>-->
<div class="status-box status-box-text">
    <?php echo $this->comments->form(); ?>
</div>
<?php echo $this->comments->display_my_comments($user, true);