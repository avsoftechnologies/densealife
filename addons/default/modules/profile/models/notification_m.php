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
    
    public function __construct()
    {
        parent::__construct() ;
        $this->_table = 'notifications' ;
       
    }
    
    public function get_unseen_count()
    {
        return $this->select('type, count(type) as count', false)
                ->group_by('type')
                ->get_many_by(
                        array(
                            'rec_id' => $this->current_user->id,
                            'status' => self::NOTIFICATION_STATUS_UNSEEN
                        )
                );   
    }
    
    public function insert($data, $skip_validation = false)
    {
        return parent::insert($data);
    }
    
    public function set_status($status, $where)
    {
        return $this->db->update($this->_table, array('status' => $status), $where);
    }
}
