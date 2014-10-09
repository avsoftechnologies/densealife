<div id="fileuploader">Upload</div>
<script>
$(document).ready(function()
{
	$("#fileuploader").uploadFile({
            url:"/photo/upload/?album_id=<?php echo $album_id;?>",
            fileName:"myfile",
            returnType:'json',
            formData: {
                username: '{{user:username}}'
            },
            showStatusAfterSuccess : false,
            showProgress: true,
            afterUploadAll: true,
            onSuccess : function(files, response, xhr){
                if(response.status){
                    $('#photoframe').prepend(response.html);
                    $file_count = $('.album_file_count_<?php echo $album_id;?>');
                    $($file_count).text(parseInt($file_count.text()) + 1);
                }
            }
            
	});
});
</script>
<div class="clear">&nbsp;</div>
<div id="photoframe">
</div>