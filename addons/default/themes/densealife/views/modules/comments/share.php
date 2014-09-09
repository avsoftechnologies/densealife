<form method="post" action="/comments/share" id="form-share">
    <input type="hidden" value="<?php echo $post->id;?>" name='fk_comment_id'/>
    <table border="0">
        <tr>
        <td colspan="2">
            <textarea placeholder="Enter your message here..." cols="40" style="width:100%;" name="comment"></textarea>
        </td>
        </tr>
        <tr>
            <?php if ($post->media != ''): ?>
            <td>
                <?php $media = unserialize($this->encrypt->decode($post->media)); ?>
                <a class="fancybox-effects-b pl10" data-fancybox-group="button" href="<?php echo $media['data']['path']; ?>"><img src="<?php echo base_url(); ?>files/medium/<?php echo $media['data']['id']; ?>" alt="" width="200" height="200"/></a> 
            </td>
        <?php endif; ?>
        <td style="vertical-align: top;">
            <?php echo $post->comment; ?> 
        </td>
        </tr>
        <tr>
        <td colspan="2" style='float:right;'><input type="submit" value="Share"/></td>
        </tr>
    </table>
</form>
