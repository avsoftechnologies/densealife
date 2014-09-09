<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Social extends Public_Controller
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
        $this->load->model('social_m');
        
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
                ->append_js('module::social.js')
                ->set('_user', $user);
        
        return $user; 
    }
    
    public function create()
    {
        $this->template
                    ->set_layout(false);
        
        $data = array( 'user_id'     => $this->current_user->id);
        if($this->input->post()){
            $data = $data+$this->input->post();
        }
        $already_exists = (boolean)$this->social_m->count_by(array('user_id' => $this->current_user->id));            
        if ( $already_exists ) {
            if($updated = $this->social_m->update_row($this->current_user->id, $data)){
                $this->template
                        ->build_json(array( 'status' => 'success' ));
            }else{
                $this->template
                        ->build_json(array( 'status' => 'failure' ));
            }
        } else {
            if ( $inserted = $this->social_m->insert($data) ) {
                $this->template
                        ->build_json(array( 'status' => 'success' ));
            } else {
                $this->template
                        ->build_json(array( 'status' => 'failure' ));
            }
        }
    }
    
    private function _form_elements()
    {
        $elements = array();
        $elements['facebook'] = array('title' => 'Facebook', 'image' => 'facebook-icon.png', 'value' =>'');
        $elements['twitter'] = array('title' => 'Twitter', 'image' => 'twitter-icon.png', 'value' =>'');
        $elements['instagram'] = array('title' => 'Instagram', 'image' => 'instagram.png', 'value' =>'');        
        $saved_entries = $this->social_m->get_by(array('user_id' => $this->current_user->id));
        if(!empty($saved_entries)){
            foreach($elements as $key => &$element){
                $element['value'] = $saved_entries->$key;
            }
        }
        return $elements;
        
    }
    public function view($username = null)
    {
        $user = $this->_settings($username);  
        
        $this->template
                ->set('elements', $this->_form_elements())
                ->build('social/form');
        
    }
    
}
