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
class Privacy_m extends MY_Model
{
    public function __construct()
    {
        parent::__construct() ;
        $this->_table = 'user_privacy_settings' ;
    }
    
    public function get_settings($user_id = null)
    {
        $user_id = $user_id == '' ? $this->current_user->id : $user_id;
        return parent::get_many_by(array('user_id' => $user_id));
    }
    public function insert($data, $skip_validation = false)
    {
        return parent::insert($data, $skip_validation);
    }
    
    public function insert_many($data, $skip_validation = false)
    {
        return parent::insert_many($data, $skip_validation);
    }
    
    public function update_row($key, $value, $data)
    {

       foreach($data as $item){
        $this->db->update($this->_table, $item, array($key => $value,'param' => $item['param']));
       }
    }
    
    public function seek_permission($user_id, $type)
    {
        $logged_in_user = $this->current_user->id; 
        $settings = $this->get_settings($user_id);
        
        $granted = true;
        foreach ( $settings as $setting ) {
            
            if ( $setting->param == $type && $setting->value == 'friend' ) {
                $this->load->model('friend/friend_m');
                $granted = $this->friend_m->is_friend($user_id);
                break;
            }
        }
        return $granted; 
    }
}
