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
class Social_m extends MY_Model
{
    public function __construct()
    {
        parent::__construct() ;
        $this->_table = 'user_social_links' ;
    }
    
    public function get_links($user_id = null)
    {
        $user_id = $user_id == '' ? $this->current_user->id : $user_id;
        return parent::get_many_by(array('user_id' => $user_id));
    }
    public function insert($data, $skip_validation = false)
    {
        return parent::insert($data, $skip_validation);
    }
    
    public function update($primary_value, $data, $skip_validation = false)
    {
        parent::update($primary_value, $data, $skip_validation);
    }
    public function insert_many($data, $skip_validation = false)
    {
        return parent::insert_many($data, $skip_validation);
    }
    public function update_row($user_id, $data)
    {
        return $this->db->update($this->_table, $data,array('user_id' => $user_id)); 
    }
}
