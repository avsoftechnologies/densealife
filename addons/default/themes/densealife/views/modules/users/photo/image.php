<div class="single-img fl txt-center mr5 photo_<?php echo $result['data']['id'];?>">
    <div>
        <a class="fancybox" data-fancybox-group="gallery" href="<?php echo base_url(str_replace('{{ url:site }}', '', $result['data']['path'])); ?>">
            <img src="<?php echo base_url('files/thumb/' . $result['data']['id']); ?>/135/135/fit" alt=""/>
        </a>
    </div>
    <div class="clear">&nbsp;</div>
    <div class="fs10 action d-none">
        <a class="fancybox" data-fancybox-group="gallery" href="<?php echo base_url(str_replace('{{ url:site }}', '', $result['data']['path'])); ?>">
            Enlarge
        </a>
        | 
        <a href="javascript:album.delete_photo('<?php echo $result['data']['id'];?>')">Delete</a>
    </div>
</div>
<script>
$('body').on('mouseover', '.single-img', function(){
    $(this).children('div.action').removeClass('d-none'); 
});
$('body').on('mouseout', '.single-img', function(){
    $(this).children('div.action').addClass('d-none'); 
});
</script>
