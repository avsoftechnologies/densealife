<?php echo form_open("trends/create",'class="form-trend"'); ?>
<?php echo form_hidden('data', $data); ?>
<button class="btn-favorite common ctrl_trend"><?php echo $text;?></button>
<?php echo form_close();?>
<script>
$(document).ready(function(){
    $('.btn-favorite').hover(function(){
       if($(this).text() == 'Favorite'){
           $(this).text('Remove Favorite');
       } 
    }, function(){
       if($(this).text() == 'Remove Favorite'){
           $(this).text('Favorite');
       } 
    });
});
</script>