<div class="comman-heading">Join the Conversation</div>
{{if allow_comment}}
<!--<ul class="status">
    <li>
        <span>{{ theme:image file="status.png" alt="Status" }}</span>
        <a href="javascript:void(0);" class="wall-status" data-type="text">Post Status</a>
    </li>
    <li>
        <span>{{ theme:image file="addvidoes.png" alt="Videos" }}</span>
        <a href="javascript:void(0);" class="wall-status" data-type="image-video" data-event="<?php echo $event->id;?>" data-title ='<?php echo $event->title;?>'>Add Photos/Video</a>
    </li>
</ul>-->
<div class="status-box status-box-text">
    <?php echo $this->comments->form(); ?>
</div>
{{else}}
<section>
    <div style='border-bottom: 2px solid skyblue;
    color: #05a6cc;
    font-size: 14px;
    padding: 58px;
    text-align: center;'>
        {{message}}
        
        <div style='margin-left:160px;'>
            {{button:follow_event event_id='<?php echo $event->id; ?>'}}
        </div>
    </div>
</section>
{{endif}}

<?php echo $this->comments->display($allow_comment);