<?php

if ( !defined('BASEPATH') )
    exit('No direct script access allowed') ;

/**
 *
 * The events manager module allows administrators to manage events and to display them to users.
 *
 * @author    Ankit Vishwakarma <ankitvishwakarma@sify.com>
 */
class Friend extends Public_Controller
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
            $this->session->userdata('redirect_url','/eventsmanager/friend');
            redirect('users/login');
        }
    }
    
    public function index(){
        echo 'hello';
    }
    public function add_friend(){ 
        echo 'hello'; exit; 
        if ( $this->input->is_ajax_request() ) {
            
            $friend_id = $this->input->post('user');
            $id = $this->_send_friend_request($friend_id) && ($friend_id != $this->current_user->id) ;
            if ( $id ) {
                echo json_encode(array( 'status' => 'success' )) ;
            } else {
                echo json_encode(array( 'status' => 'failure' )) ;
            }
        }
    }
    
    private function _send_friend_request($friend_id)
    {
        return $this->friend_m->send_friend_request($friend_id);
    }
    
    public function respond_friend_request($request_id, $response){
        $updated = $this->friend_m->response($request_id, $response);
        if($updated){
            echo json_encode(array('status' => 'success'));
        }else{
            echo json_encode(array('status' => 'failure'));
        }
    }
    
}
