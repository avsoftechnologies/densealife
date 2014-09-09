<?php

if ( !defined('BASEPATH') )
    exit('No direct script access allowed') ;

/**
 *
 * The events manager module allows administrators to manage events and to display them to users.
 *
 * @author    Ankit Vishwakarma <ankitvishwakarma@sify.com>
 */
class Notification extends Public_Controller
{

    /**
     * Constructor method
     *
     * @author Ankit Vishwakarma <ankitvishwakarma@sify.com>
     * @access public
     * @return void
     */
   
    public function __construct()
    {
        parent::__construct() ;       
        if(!is_logged_in()){
            $this->session->userdata('redirect_url', 'friend/notification');
            redirect('users/login');
        }
    }
    
    public function new_friend(){
        $user_id = $this->current_user->id; 
    }
    
    
}
