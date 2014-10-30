<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications extends Public_Controller
{

    public $user;
    public $page_location;
    
    public function __construct()
    {
        parent::__construct();
        if (!is_logged_in()) {
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
        if ($this->input->is_ajax_request()) {
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
        // get all unseen notification for a user 
        $notifications = $this->notification_m
                ->select('default_notifications.*, p.display_name as sender_name, u.username')
                ->join('profiles as p', "p.user_id = default_notifications.sender_id", 'left')
                ->join('users as u', 'p.user_id = u.id', 'inner')
                ->get_many_by(array(
                    'rec_id' => $this->current_user->id,
                     //'default_notifications.status' => 'seen'
                    ));
        $this->template
                ->set('notifications', $notifications)
                ->build('notifications/view');
    }
    
    public function pending_requests()
    {
        if($type = $this->input->post('type')) {
            $notifications = $this->notification_m
                ->select('default_notifications.*, p.display_name as sender_name')
                ->join('profiles as p', "p.user_id = default_notifications.sender_id", 'left')
                ->get_many_by(array('rec_id' => $this->current_user->id, 'type' => $type));
        
            $this->notification_m->set_status(
                'seen',array(
                        'status' => 'unseen',
                        'type' => $type,
                        'rec_id' => $this->current_user->id
                    )
            );
            if($type == 'friend') {
                $view = 'friends_awaiting';
            }
            if (count($notifications)) {
                $this->template
                        ->set('notifications', $notifications)
                        ->set('type', $type)
                        ->build('notifications/'. $view);
            } else {
                echo 'false';
            }
        }
    }
    
    public function pending_count()
    {
        $pending = $this->notification_m->get_unseen_count();
        $this->template
                ->build_json(array('notifications' => $pending));
    }

    public function unseen_message()
    {
        $notifications = $this->notification_m
                ->select('default_notifications.*, p.display_name as sender_name')
                ->join('profiles as p', "p.user_id = default_notifications.sender_id", 'left')
                ->where('type', Notify::TYPE_MESSAGE)
                ->get_many_by(array('rec_id' => $this->current_user->id));
        $this->db->where(
                array(
                    'rec_id' => $this->current_user->id,
                    'status' => 'unseen'));
        $this->db->where('type', Notify::TYPE_MESSAGE);
        $this->db->update('notifications', array('status' => 'seen'));
        if (count($notifications)) {
            $this->template
                    ->set('notifications', $notifications)
                    ->build('notifications/message');
        } else {
            echo 'false';
        }
    }

    public function unseen_other()
    {
        $notifications = $this->notification_m
                ->select('default_notifications.*, p.display_name as sender_name')
                ->join('profiles as p', "p.user_id = default_notifications.sender_id", 'left')
                ->where_not_in('type', array(Notify::TYPE_FRIEND, Notify::TYPE_MESSAGE))
                ->get_many_by(array('rec_id' => $this->current_user->id));
        $this->db->where(
                array(
                    'rec_id' => $this->current_user->id,
                    'status' => 'unseen'));
        $this->db->where_not_in('type', array(Notify::TYPE_FRIEND, Notify::TYPE_MESSAGE));
        $this->db->update('notifications', array('status' => 'seen'));
        if (count($notifications)) {
            $this->template
                    ->set('notifications', $notifications)
                    ->build('notifications/others');
        } else {
            echo 'false';
        }
    }

    public function approval()
    {
        if ($_POST) {
            $this->load->model('auto_approval_m');
            $this->auto_approval_m->insert(isset($_POST['followers']) ? $_POST['followers'] : array());
        }
        $this->load->model('trends/trend_m');
        $my_event_followers = $this->trend_m->get_my_event_followers();
        $this->template
                ->set('followers', $my_event_followers)
                ->set('title', 'My Events\' followers')
                ->build('notifications/approval');
    }

}
