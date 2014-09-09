<style>
    .suggest_item{margin-left: 11px !important;
    margin-top: 12px !important;
    overflow: hidden;
    width: 414.6px!important;}
</style>
To :<input type="text" name="to" id="suggest" /> 
<!--<input type="text" name="to" id="to"/>-->
<input type="hidden" name="action" value="new_message"/>


<script language="javascript" type="text/javascript">
        $("#suggest").coolautosuggest({
                url:"/messages/search2/?term=",
                showThumbnail: true,
                showDescription: true
    });
</script>

