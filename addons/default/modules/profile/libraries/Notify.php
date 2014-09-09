<?php

/**
 * Description of Notify
 *
 * @author Ankit Vishwakarma <admin@avsoftechnologies.in>
 */
class Notify
{
    CONST TYPE_FRIEND = 'friend';
    CONST TYPE_MESSAGE = 'message';
    CONST TYPE_FOLLOW  = 'follow';
    CONST TYPE_INVITE  = 'invite';
    CONST TYPE_SHARE   = 'share';
    CONST TYPE_COMMENT = 'comment';
    
    public static function trigger($type, $data)
    {
        $insert['sender_id'] = ci()->current_user->id; 
        $insert['rec_id'] = $data['rec_id'];
        $insert['type']= $type;
        $insert['data'] = serialize($data);
        
        try{
            ci()->notification_m->insert($insert);
        }catch(Exception $e){
            log_message('error', $e->getMessage());
        }
    }
}
