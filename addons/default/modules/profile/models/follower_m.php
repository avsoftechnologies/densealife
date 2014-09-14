<?php

defined('BASEPATH') or exit('No direct script access allowed') ;

/**
 * 
 * @author      Ankit Vishwakarma <ankitvishwakarma@sify.com>
 */
class Follower_m extends MY_Model
{
    public function __construct()
    {
        parent::__construct() ;
        $this->_table = 'user_followers' ;
    }
    /**
     * 
     * @param type $user_id
     * @return type
     */
    public function get_followers($user_id)
    {
        $followers = $this->select('p.*')
                ->join('profiles as p', 'p.user_id = '.$this->_table.'.follower_id', 'inner')
                ->get_many_by(array($this->_table.'.user_id' => $user_id));
       
        return $followers;
    }
    
    /**
     * 
     * @param type $user_id
     * @return type
     */
    public function get_followings($user_id)
    {
        $followers = $this->select('p.*')
                ->join('profiles as p', 'p.user_id = '.$this->_table.'.user_id', 'inner')
                ->get_many_by(array($this->_table.'.follower_id' => $user_id));
       
        return $followers;
    }
    
    /**
     * 
     * @param int $user_id user id
     * @return int
     */
    public function count_followings($user_id)
    {
        $count = parent::count_by(array('follower_id' => $user_id)); 
        return ($count ? $count : "-") ;
    }
    
    /**
     * 
     * @param type $user_id
     * @return type
     */
    public function count_followers($user_id)
    {
        return parent::count_by(array('user_id' => $user_id)); 
    }
    
    /**
     * 
     * @param type $user_id
     * @param type $follower_id
     * @return type
     */
    public function is_follower($user_id, $follower_id)
    {
         return parent::count_by(array('user_id' => $user_id, 'follower_id' => $follower_id,'status' =>'following')) ? true : false ;

    }
    
    /**
     * 
     * @param type $data
     * @param type $skip_validation
     * @return type
     */
    public function follow($data, $skip_validation = false)
    {
      return parent::insert($data, $skip_validation);
    }
    
    
    
}
