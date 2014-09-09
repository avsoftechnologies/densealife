<?php if($images):?>
<?php foreach($images as $image):?>
<a class="fancybox fl mr10" data-fancybox-group="gallery" href="<?php echo $image->path;?>">
    <img src="{{url:site}}files/medium/<?php echo $image->id;?>/fit" alt="" width="200" height="200"/>
</a>
<?php endforeach;?>
<?php endif;
