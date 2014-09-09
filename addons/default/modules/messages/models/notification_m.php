<?php

defined('BASEPATH') or exit('No direct script access allowed');

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

    CONST NOTIFICATION_TYPE_FRIEND = 'friend';
    
    public function __construct()
    {
        parent::__construct();
        $this->_table = 'notifications';
    }
    
    public function get_notifications($type)
    {
        $result = $this->db->select('p.*')
                ->from('notifications as n')
                ->join('profiles as p', 'p.user_id = n.sender_id', 'inner')
                ->where(array('status' => 'unseen',
                    'type' => $type, 
                    'rec_id' => $this->current_user->id))
                ->get()
                ->result_array();
        
        return $result;
    }
    

    

}
