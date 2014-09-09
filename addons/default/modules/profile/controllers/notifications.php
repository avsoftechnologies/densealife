<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications extends Public_Controller
{

    public $user;
    public $page_location;

    public function __construct()
    {
        parent::__construct();
        if ( !is_logged_in() ) {
            redirect('users/login');
        }
        $this->lang->load('profile');
        $this->load->model('notification_m');
        $this->load->model('friend/friend_m');
        $this->template->set('user', $this->current_user);

        $friends    = $this->friend_m->get_friends($this->current_user->id);
        $this->user = $this->current_user;

        $this->template
                ->set_layout('densealife')
                ->set('friends', $friends)
                ->append_js('wall.js')
                ->append_js('module::profile.js');
        if ( $this->input->is_ajax_request() ) {
            $this->template
                    ->set_layout(false);
        }
    }

    public function index()
    {
        $this->view();
    }

    public function view()
    {
        $pending = $this->notification_m->get_all_notifications($this->user->id);
        $this->template
                ->append_js('module::notification.js')
                ->set('notifications', $pending)
                ->build('notifications/view');
    }

    public function pending()
    {
       $pending =  $this->notification_m->get_unseen_count($this->user->id, 'type');
       $this->template
               ->build_json(array('notifications' => $pending));
    }
    
    public function unseen_message()
    {        
        $notifications = $this->notification_m->get_message_notification();
        $this->notification_m->set_status('seen', array(
                    'rec_id' => $this->current_user->id,
                    'status' =>'unseen',
                    'type' => 'message'));
        if(count($notifications)){
        $this->template
                ->set('notifications', $notifications)
                ->build('notifications/unseen_message');
        }else{
            echo 'false';
        }
    }
    
    public function friends_awaiting()
    {
        $notifications = $this->notification_m->get_friend_notifications();
        $this->notification_m->set_status('seen', array(
                    'rec_id' => $this->current_user->id,
                    'status' =>'unseen',
                    'type' => Notify::TYPE_FRIEND));
        if(count($notifications)){
        $this->template
                ->set('notifications', $notifications)
                ->build('notifications/friends_awaiting');
        }else{
            echo 'false';
        }
    }
    
     public function unseen_other()
    {
        $notifications = $this->notification_m->get_other_notifications();
        
        $this->db->where(
                                        array(
                                                'rec_id' => $this->current_user->id,
                                                'status' =>'unseen'));
        $this->db->where_not_in('type', array(Notify::TYPE_FRIEND, Notify::TYPE_MESSAGE));
        $this->db->update('notifications',array('status' => 'seen'));
        
        if(count($notifications)){
        $this->template
                ->set('notifications', $notifications)
                ->build('notifications/unseen_other');
        }else{
            echo 'false';
        }
    }
    
    public function approval()
    {
        if($_POST){
            $this->load->model('auto_approval_m'); 
            $this->auto_approval_m->insert(isset($_POST['followers']) ? $_POST['followers'] : array()); 
        }
        $this->load->model('trends/trend_m');
        $my_event_followers  = $this->trend_m->get_my_event_followers();
        $this->template
                ->set('followers', $my_event_followers)
                ->set('title', 'My Events\' followers')
                ->append_js('module::notification.js')
                ->build('notifications/approval');
    }
}
