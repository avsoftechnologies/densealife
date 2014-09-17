<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Privacy extends Public_Controller
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
        $this->load->model('privacy_m');
        
    }
    
    public function get_privacy_parameters()
    {
        $setting = array(
            'can_view_my_profile' => array(
                            'title' =>'Who can view my profile?', 
                            'values' => array( 'public', 'friend' ),
                            'default' => 'friend'),
            
            'can_search_for_me'   => array(
                            'title' =>'Who can search for me?',
                            'values' => array( 'public', 'friend' ),
                            'default' => 'friend'),
            
            'can_see_my_activity' => array(
                            'title' =>'Who can see my activity?', 
                            'values' => array( 'public', 'friend' ),
                            'default' => 'friend'),
            
            'can_message_me'      => array(
                            'title' =>'Who can message me?',
                            'values' => array( 'public', 'friend' ),
                            'default' => 'friend'),
        );
        
        $saved = $this->privacy_m->get_settings();
        //p($setting);
        if(!empty($saved)){
            foreach($setting as $key => &$value){
                foreach($saved as $my_setting){
                    if($key == $my_setting->param){
                        $value['default'] = $my_setting->value;
                        break;
                    }
                }
            }
        }        
        
        return $setting;
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
    
    public function create()
    {
        $post   = $this->input->post();
        
        $params = $this->get_privacy_parameters();
        $data   = array();
        foreach ( $post as $key => $value ) {
            $item = array();
            if ( array_key_exists($key, $params) and in_array($value, $params[$key]['values']) ) {
                $item['user_id'] = $this->current_user->id;
                $item['param']   = $key;
                $item['value']   = $value;
                $data[]          = $item;
            }
        }
        if ( $this->privacy_m->count_by(array( 'user_id' => $this->current_user->id )) ) {
            $this->privacy_m->update_row('user_id',$this->current_user->id, $data);
            redirect('/profile/privacy');
        } else {
            if ( $this->privacy_m->insert_many($data) ) {
                redirect('/profile/privacy');
            }
        }
    }
    
    public function view($username = null)
    {
        $user = $this->_settings($username);  
        $privacy_paramenters = $this->get_privacy_parameters();
        $this->template
                ->set('_user', $user)
                ->set('parameters', $privacy_paramenters)
                ->build('privacy/view');
    }

}
