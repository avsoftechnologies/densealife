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
        if ( !is_logged_in() ) {
            $this->session->userdata('redirect_url', '/eventsmanager/friend') ;
            redirect('users/login') ;
        }
        $this->load->model('friend_m') ;
    }

    public function index()
    {
        
    }
 

    public function add()
    {
        if ( $this->input->is_ajax_request() ) {

            $friend_id = $this->input->post('user') ;
            $id        = $this->_send_friend_request($friend_id) && ($friend_id != $this->current_user->id) ;
            if ( $id ) {
                echo json_encode(array( 'status' => 'success' )) ;
            } else {
                echo json_encode(array( 'status' => 'failure' ,'msg' => 'Friend request already sent to the user.')) ;
            }
        }
    }
    
    public function unfriend()
    {
        if ( $this->input->is_ajax_request() ) {

            $friend_id = $this->input->post('user') ;
            $id        = $this->friend_m->unfriend($friend_id);
            if ( $id ) {
                echo json_encode(array( 'status' => 'success' )) ;
            } else {
                echo json_encode(array( 'status' => 'failure' )) ;
            }
        }
    }
    
    public function respond_friend()
    {

        if ( $this->input->is_ajax_request() ) {

            $friend_id = $this->input->post('user') ;
            $id        = $this->_respond_friend_request($friend_id) && ($friend_id != $this->current_user->id) ;
            if ( $id ) {
                echo json_encode(array( 'status' => 'success' )) ;
            } else {
                echo json_encode(array( 'status' => 'failure' )) ;
            }
        }
    }

    private function _send_friend_request($friend_id)
    {
        return $this->friend_m->send_friend_request($friend_id) ;
    }
    
     private function _respond_friend_request($friend_id)
    {
        return $this->friend_m->respond_friend_request($friend_id) ;
    }
    public function response_request($request_id, $response)
    {
        
         if ( $this->input->is_ajax_request() ) {

            $friend_id = $this->input->post('user') ;
            $id        = $this->_send_friend_request($friend_id) && ($friend_id != $this->current_user->id) ;
            if ( $id ) {
                echo json_encode(array( 'status' => 'success' )) ;
            } else {
                echo json_encode(array( 'status' => 'failure' )) ;
            }
        }
        $updated = $this->friend_m->response($request_id, $response) ;
        if ( $updated ) {
            echo json_encode(array( 'status' => 'success' )) ;
        } else {
            echo json_encode(array( 'status' => 'failure' )) ;
        }
    }

    
    public function check_pending_notification()
    {
        $this->load->model('messages/notification_m');
        $notifications = $this->notification_m->get_notifications('friend');
        
        $this->template->build_json(array('count' => count($notifications))) ;
    }
}
