{{ theme:partial name='metadata' }}
<style>
    body{background: none;}
    html, ul, li{background-color:#F9F9F9}
    ul{list-style-type: none !important;}
    ul.followers li {
        float: left;
        margin: 2px 1px;
        width: 49%;
    }
</style>

<script>
    $('#invite').ajaxForm({
        type: 'POST',
        delegation: true, // for live response
        dataType: 'json',
        beforeSubmit: function() {
            $.fancybox.showLoading();
        },
        success: function(response) {
            if(response.status=='error' && response.invalid_emails != ''){
                $('#email_ids').val(response.invalid_emails).next('.msg').text(response.msg).css({'color':'red'});
            }else{
                $('#email_ids').val('').next('.msg').text(response.msg).css({'color':'green'});
                setTimeout(function(){parent.$.fancybox.close();}, 3000)
            }
        },
        error: function() {
            alert('error')
        },
        complete: function() {
            $.fancybox.hideLoading();
        }
    });
</script>
<div class="comman-heading">Invite Friends</div>
<br class='clear'/>
<form method='post' id='invite'>
    <div class='mt25'>
        <span class='fl'>
            <input type='hidden' name='event_slug' value='<?php echo $event->slug;?>'/>
            <input type='text' style='width:487px !important;' name='friends' id='email_ids'> 
            <span class='msg ml10'></span>
        </span>
        <br class='clear'/>
        <span class='fr'>
            <button class="btn-color common" onclick="$('#invite').submit();
                    return false;">Invite</button>
        </span>
    </div>
</form>