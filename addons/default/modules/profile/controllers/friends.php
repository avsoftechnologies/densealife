<?php
error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');

class Friends extends Public_Controller
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
        $this->load->model('users/profile_m');
        
    }
    
    public function index($username = null)
    {
        $this->view($username);
    }
    
    private function _settings($username)
    {
        $this->template->set_layout('user')
                ->append_js('wall.js')
                ->append_js('module::profile.js')
                ->append_js('module::page.js');
        if($this->input->is_ajax_request()){
            $this->template
                ->set_layout(false);
        }
        // work out the visibility setting
        switch ( Settings::get('profile_visibility') ) {
            case 'public':
                // if it's public then we don't care about anything
                break ;

            case 'owner':
                // they have to be logged in so we know if they're the owner
                $this->current_user or redirect('users/login/users/view/' . $username) ;

                // do we have a match?
                $this->current_user->username !== $username and redirect('404') ;
                break ;

            case 'hidden':
                // if it's hidden then nobody gets it
                redirect('404') ;
                break ;

            case 'member':
                // anybody can see it if they're logged in
                $this->current_user or redirect('users/login/users/view/' . $username) ;
                break ;
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
                ->set('username', $user->username);
        
        return $user; 
    }
    
    public function find($username = null)
    {
        $user = $this->_settings($username);
        $this->form_validation->set_rules('name', 'Name', 'trim|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|xss_clean|valid_email');
        $this->form_validation->set_rules('location', 'Location', 'trim|xss_clean');

        $this->form_validation->set_error_delimiters('<br /><span class="error">', '</span>');

        if ( $this->form_validation->run() === TRUE ) { // passed validation proceed to post success logic
            // build array for the model

            $form_data = array(
                'name'     => set_value('name'),
                'email'    => set_value('email'),
                'location' => set_value('location'),
                'gender'   => set_value('gender')
            );

            $this->profile_m->select('profiles.*, u.email')
                    ->join('users as u', 'u.id = profiles.user_id', 'inner');
            if ( $this->input->post('name') ) {
                $this->profile_m->like('profiles.first_name', set_value('name'))
                        ->or_like('profiles.last_name', set_value('name'));
            }
            if ( $this->input->post('email') ) {
                $this->profile_m->like('u.email', set_value('email'));
            }
            if ( $this->input->post('location') ) {
                $this->profile_m->like('profiles.address_line1', set_value('location'))
                        ->or_like('profiles.address_line2', set_value('location'))
                        ->or_like('p.address_line3', set_value('location'));
            }


            $result = $this->profile_m->get_many_by(array( 'u.active' => 1, 'profiles.user_id!=' => $this->current_user->id ));
            $this->template
                    ->set('result', $result);
        }
        
        $this->template
                ->set('_user', $user)
                ->set('user_friends', $user_friends) // commented on 11 august 2014
				//->set('user_friends', '')
                ->build('friends/find');
    }
    
    public function invite()
    {
        $user = $this->_settings($this->current_user->username);
        $this->template->set('msg','');
        $this->form_validation->set_rules('email', 'Email', 'trim|xss_clean|valid_email');        

        $this->form_validation->set_error_delimiters('<br /><span class="error">', '</span>');

        if ( $this->form_validation->run() === TRUE ) { // passed validation proceed to post success logic
            // build array for the model
            $invite['slug']         = 'invite-friend';
            $invite['to']           = set_value('email');
            $invite['reply-to']     = $user->email;
            $invite['user']         = $user->display_name;
            
            if(@Events::trigger('email', $invite)){
                if($this->input->is_ajax_request()){
                    $this->template->set_layout('false')
                        ->build_json(array('status'=>'success', 'msg' => 'An Invitation mail successfully sent...'));
                }else{
                    $this->template->set('msg','An Invitation mail successfully sent...')
                            ->set('_user', $user)
                        ->build('friends/find');;
                }
            }
        }else{
        $this->template
                ->set('_user', $user)
                ->build('friends/find');
        }
    }

    public function create()
    {
        
    }
    
    public function view($username = null)
    {
        $user = $this->_settings($username);
        $this->load->model('friend/friend_m');
        $followers = $this->friend_m->get_friends($user->id);
        $this->template
                ->set('followers', $followers)
                ->set('_user', $user)
                ->set('title' , 'My Friends')
                ->build('followers/view');
        
    }

    public function pending_requests($username = null)
    {
        $user = $this->_settings($username);  
        $this->load->model('friend/friend_m');
        $requests = $this->friend_m->get_pending_requests();
        $this->template
                ->set('requests', $requests)
                ->set('title', 'Pending Requests')
                ->set('_user', $user)
                ->build('friends/pending');
    }
    
     public function invite_event()
    {
        $user = $this->_settings($this->current_user->username);
        $friend_id = $this->input->post('friend_id');
        $event_id = $this->input->post('entry_id');
        
        $this->load->model('eventsmanager/eventsmanager_m');
        $event = $this->eventsmanager_m->get_by(array('id' => $event_id));
        $user = $this->ion_auth->get_user_array($friend_id) ;
        
        $invite['slug']     = 'invite-friend-to-event';
        $invite['to']       = $user['email'];
        $invite['reply-to'] = $this->current_user->email;
        $invite['username']     = $user['display_name'];
        $invite['title'] = $event->title;
        if ( $this->input->is_ajax_request()  && @Events::trigger('email', $invite) ) {            
                $invite['rec_id'] = $friend_id;
                Notify::trigger(Notify::TYPE_INVITE, $invite);
                $this->template->set_layout('false')
                        ->build_json(array( 'status' => 'success', 'msg' => 'An Invitation mail successfully sent...' ));
        }
    }
}
