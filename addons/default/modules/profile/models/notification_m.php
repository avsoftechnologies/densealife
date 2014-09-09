<?php

defined('BASEPATH') or exit('No direct script access allowed') ;

/**
 *
 *
 * @author      Ankit Vishwakarma <ankitvishwakarma@sify.com>
 * @package 	PyroCMS
 * @subpackage 	Events Manager Module
 * @category 	Modules
 * @license 	Copyright
 */
class Notification_m extends MY_Model
{
    
    CONST TYPE_FRIEND = 'friend';
    CONST TYPE_MESSAGE = 'message';
    CONST TYPE_SOMEONE_LIKE_PHOTO = 'someone_like_photo';
    
    CONST NOTIFICATION_STATUS_UNSEEN = 'unseen';
    
    private $_notifications = array();
    
    public function __construct()
    {
        parent::__construct() ;
        $this->_table = 'notifications' ;
       
    }
    public function get_columns($colums = true)
    {
        if($colums){
            $this->select("p1.display_name as receiver,
                    p2.display_name as sender,
                    p1.user_id as receiver_id, 
                    p2.user_id as sender_id,
                    $this->_table.created_on,")
                ->select($this->_table . '.type');
        }
        return $this
                ->select($this->_table.'. data')
                        ->join('profiles as p1', "p1.user_id = $this->_table.rec_id", 'inner')
                        ->join('profiles as p2', "p2.user_id = $this->_table.sender_id", 'inner')
                        ->where(
                                array(
                                    //$this->_table . '.status' => self::NOTIFICATION_STATUS_UNSEEN,
                                    $this->_table . '.rec_id' => $this->current_user->id
                                )
                )
            ->order_by('created_on', 'DESC');
    }
    
    public function get_all_notifications()
    {
        return $this->get_columns()->get_all();
    }
    
    public function get_friend_notifications()
    {
        return $this->get_columns()->get_many_by(
                    array(
                            $this->_table.'.type' => self::TYPE_FRIEND,
                    )
               );
    }
    
    public function get_message_notification()
    {
        return $this->get_columns()->get_many_by(
                    array(
                            $this->_table.'.type' => self::TYPE_MESSAGE,
                    )
               );
    }
    
    public function get_other_notifications()
    {
        return $this->get_columns()
                ->where_not_in('type',array(self::TYPE_FRIEND, self::TYPE_MESSAGE))
                ->get_all();
    }
    
    
    public function get_someone_likes_my_photo()
    {
        
    }
    
    public function get_someone_start_following_me($user_id)
    {
        
    }
    
    public function get_someone_mentioned_me($user_id)
    {
        
    }
    
    public function get_unseen($user_id, $group = null)
    {
        $unseen = $this->get_all_notifications($user_id);
        
        $key = $group;
        foreach($unseen as $item)
        {
            switch($group){
                case 'type':
                    $key = $item->type;
                    break;
                default:
                    $key = strtotime($item->created_on);
            }
            $this->_notifications[$key][]= $item; 
            
        }
        //echo $this->last_query();
        return $this->_notifications;
    }
    
    public function get_unseen_count()
    {
        $p = $this->get_columns(false)->select('count(type) as count, type')->group_by('type')->get_all();
        return $p;
    }
    
    public function insert($data, $skip_validation = false)
    {
        return parent::insert($data, $skip_validation);
    }
    
    public function set_status($status, $where)
    {
        return $this->db->update($this->_table, array('status' => $status), $where);
    }
}
