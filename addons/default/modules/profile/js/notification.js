var notification = {
    approve : function(comment_id) {
        $.get('/comments/approve', {cid: comment_id, action:'approve'}, function(response){
            if(response.success!=''){
                alert(response.success);
                $.fancybox.close();
            }
        },'json');
    },
    
    decline : function(comment_id) {
         $.get('/comments/approve', {cid: comment_id, action:'unapprove'}, function(response){
            if(response.success!=''){
                alert(response.success);
                $.fancybox.close();
            }
        },'json');
    },
    
    block : function(user_id) {
         $.get('/comments/block', {uid:user_id}, function(response){
            if(response.success!=''){
                alert(response.success);
                $.fancybox.close();
            }
        },'json');
    },
    auto_approve : function(){
        //this is commented because in case of only one follower if selected i want to deselect it then it would be a probls. 
//        if($('input[name="followers[]"]:checked').length == 0 ){
//            alert('Please select one from the list to approve');
//            return false; 
//        }
    }
}