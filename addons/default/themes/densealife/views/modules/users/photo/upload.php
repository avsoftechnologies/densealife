<div id="fileuploader">Upload</div>
<script>
$(document).ready(function()
{
	$("#fileuploader").uploadFile({
            url:"/photo/upload",
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
                }
            }
            
	});
});
</script>
<div class="clear">&nbsp;</div>
<div id="photoframe">
</div>