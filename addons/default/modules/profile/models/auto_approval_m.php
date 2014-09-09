<?php

defined('BASEPATH') or exit('No direct script access allowed') ;

/**
 * 
 * @author      Ankit Vishwakarma <er.ankitvishwakarma@gmail.com>
 */
class Auto_approval_m extends MY_Model
{
    private $_dbprefix;
    CONST APPROVAL_TYPE_COMMENT = 'comment'; 
    public function __construct()
    {
        parent::__construct() ;
        $this->_dbprefix = $this->db->dbprefix;
        $this->db->set_dbprefix(null);
        $this->_table = 'auto_approvals' ;
    }
    
    public function insert($data, $skip_validation = false)
    {
        $prepare = array(); 
        $this->db->update('auto_approvals', array('modified_on' => date('Y-m-d H:i:s', now()),'status' => 'off'), array('admin_id' => $this->current_user->id));
        foreach ($data as $follower) {
            if($exist = $this->if_exists($follower)){
                parent::update($exist->id, array('status' => 'on', 'modified_on' => date('Y-m-d H:i:s', now())));
            }else{
                $prepare= array('admin_id'      => $this->current_user->id,
                    'user_id'       => $follower,
                    'approval_type' => self::APPROVAL_TYPE_COMMENT,
                    'status'        => 'on',
                );
                parent::insert($prepare, $skip_validation);
            }
            
        }
        $this->db->set_dbprefix($this->_dbprefix);
    }
    
    public function if_exists($follower_id) {
        return $this->select('id')->get_by(array('admin_id' => $this->current_user->id,
            'user_id' =>$follower_id,
            'approval_type' => self::APPROVAL_TYPE_COMMENT));
    }
    
    public function __destruct()
    {
        $this->db->set_dbprefix('default_'); 
    }
   
}
