<?php echo form_open("trends/create",'class="form-trend"'); ?>
<?php echo form_hidden('data', $data); ?>
<button class="btn-follow <?php echo $class;?> btn-follow-<?php echo $entry_id;?>" data-id="<?php echo $entry_id;?>"><?php echo $text;?></button>
<?php echo form_close();?>
<script>
$(document).ready(function(){
    $('.btn-follow').hover(function(){
       if($(this).text() == 'Following'){
           $(this).text('Unfollow');
       } 
    }, function(){
       if($(this).text() == 'Unfollow'){
           $(this).text('Following');
       } 
    });
});
</script>