<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Comments extends Public_Controller
{

    public $user;
    public $page_location;
    
    public function __construct()
    {
        parent::__construct();
        if (!is_logged_in()) {
            redirect('users/login');
        }
        $this->load->model('comments/comment_m');
        $this->load->model('friend/friend_m');
        $this->template->set('user', $this->current_user);
        $friends    = $this->friend_m->get_friends($this->current_user->id);
        $this->user = $this->current_user;

        $this->template
                ->set_layout('densealife')
                ->set('friends', $friends)
                ->append_js('wall.js');
//                /->append_js('module::profile.js');
        if ($this->input->is_ajax_request()) {
            $this->template
                    ->set_layout(false);
        }
    }
    
    public function index()
    {
        $this->view($comment_id);
    }
    
    public function view($comment_id)
    {
        $this->template
                ->build('comments/view');
    }
    
    
    
}
