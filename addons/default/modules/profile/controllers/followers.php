<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Followers extends Public_Controller
{

    public $user;
    public $page_location;

    public function __construct()
    {
        parent::__construct();
        if ( !is_logged_in() ) {
            $this->session->set_userdata('redirect_to', current_url());
            redirect('users/login');
        }
        $this->user = $this->current_user;
        $this->load->model('follower_m');
        
    }
    
    public function index($username = null)
    {
        $this->view($username);
    }
    
    private function _settings($username)
    {
        $this->template->set_layout('user');
        if($this->input->is_ajax_request()){
            $this->template
                ->set_layout(false);
        }
        // Don't make a 2nd db call if the user profile is the same as the logged in user
        if ( $this->current_user && $username === $this->current_user->username ) {
            $user = $this->current_user ;
        }
        // Fine, just grab the user from the DB
        else {
            $user = $this->ion_auth->get_user($username) ;
        }
        
        // No user? Show a 404 error
        $user or show_404() ;

        $this->template->append_metadata('<script> var current_user = "'.$user->username.'"</script>')
                ->set('username', $user->username)
                ->set('_user', $user);
        
        return $user; 
    }
    
    public function create()
    {
        $data = array( 
                        'user_id'     => $this->input->post('user'),
                        'follower_id' => $this->current_user->id,
                );
        $already_exists = $this->follower_m->get_by($data);
        if ( !empty($already_exists) ) {
            $label = 'Following';
            $this->db->where($data);
            if($already_exists->status =='following'){
                $set_data = array(
                    'status'            => 'unfollowing',
                    'stop_following_on' => date('Y-m-d H:i:s') );
                $label = 'Unfollowing';
            }elseif($already_exists->status =='unfollowing'){
                $set_data = array(
                    'status'            => 'following',
                    'following_since' => date('Y-m-d H:i:s') );
                $label = 'Following';
            }
            $this->db->update('user_followers', $set_data);
            $this->template
                        ->build_json(array( 'status' => 'success', 'label' => $label ));
        } else {
            $data ['following_since'] = date('Y-m-d H:i:s');

            if ( $inserted = $this->follower_m->insert($data) ) {
                $data['rec_id'] = $this->input->post('user');
                Notify::trigger(Notify::TYPE_FOLLOW, $data);
                $this->template
                        ->build_json(array( 'status' => 'success', 'label' => 'Following' ));
            } else {
                $this->template
                        ->build_json(array( 'status' => 'failure' ));
            }
        }
    }

    public function view($username = null)
    {
        $user = $this->_settings($username);  
        $followers = $this->follower_m->get_followers($user->id);
        $count_followers = $this->follower_m->count_followers($user->id);
        $this->template
                ->set('followers', $followers)
                ->set('title', 'Followers')
                ->set('count', $count_followers)
                ->build('followers/view');
        
    }
    
    
    public function following($username = null)
    {
        $user = $this->_settings($username);  
        $followings = $this->follower_m->get_followings($user->id);
        $count_followings = $this->follower_m->count_followings($user->id);
        $this->template
                ->set('followers', $followings)
                ->set('title', 'Following')
                ->set('count', $count_followings)
                ->build('followers/view');
    }
}
