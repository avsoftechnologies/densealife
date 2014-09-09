{{theme:partial name='blocks/common' image_files='{{photos}}' albums={{albums}}}}
<div class="comman-box clearfix">
    <div class="comman-heading">Photos</div>
    {{ user:profile_fields user_id= _user:id }}
    {{if user:id==_user:id}}
    <ul class="search-box clearfix">
        <li class="right"><button id="add-album">Add Album</button></li>
        <li class="right"><button id="add-photo-video" class="btn-color">Add Photo/ Video</button></li>
    </ul>
    {{endif}}
    {{user:profile}}
    <div id="container-album-create-form" class="d-none">&nbsp;</div>
    <div class="clear"></div>
    <hr/>
    <div class="clear"></div>
<!--    <fieldset>
        <legend>Photos</legend>-->
        <ul style="margin-left:20px; background-color:#CCC;">
        <?php if(count($photos)):?>
            <?php foreach($photos as $image):  ?>
            <li>
                <a class="fancybox fl mr10" data-fancybox-group="gallery" href="<?php echo $image->path;?>">
                    <img src="<?php echo base_url('files/fit/'.$image->id.'/130');?>" alt="<?php echo $image->name;?>" title="<?php echo $image->name;?>"/>
                </a>
            </li>
            <?php endforeach;?>
        <?php endif; ?>
    </ul>
    <!--</fieldset>-->
    
<!--    <fieldset>
        <legend>Albums</legend>
        <ul class="videos">
        {{albums:data:folder}}
            <li>
                <a href="/album/images/{{id}}" class="fancybox fancybox.ajax">
                <img src="{{files:image file=folder_image dim="145"}}"/>
                <span class="txt-center name wid-135 ellipsis" title="{{name}}"> 
                    {{name}} 
                </span>
                <span class="name txt-center" style="margin-top:-11px !important">({{file_count}} Photos)</span>
                </a>
            </li>
        {{/albums:data:folder}}
    </ul>
    </fieldset>-->
</div>