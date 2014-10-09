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
<div class="status-box status-box-text">
<?php if($message!=''):?>
<section>
    <table align="center">
        <tr>
            <td>{{message}}</td>
            <td>{{button:follow_event event_id='<?php echo $event->id; ?>' reload='true'}}</td>
        </tr>
    </table>
</section>
<?php endif;?>
    </div>
{{endif}}

<?php echo $this->comments->display($allow_comment, $blacklisted);