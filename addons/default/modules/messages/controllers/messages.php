<?php

/**
 * @author Ankit Vishwakarma <admin@avsoftechnologies.in>
 * @author Ankit Vishwakarma <ankitvishwakarma@sify.com>
 */
class Messages extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ( !is_logged_in() ) {
            $this->session->set_userdata('redirect_to', current_url());
            redirect('users/login');
        }
        $this->lang->load('profile/profile');
        $this->load->model('message_m');
        $this->load->model('users/user_m');
        $this->load->model('users/profile_m');
        $senders  = $this->message_m->get_senders();
        $unread = $this->message_m->count_unread();
        $this->template->set_layout('messages')
                ->append_js('module::messages.js')
                ->set('senders', $senders)
                ->set('unread', $unread);
        
    }
    
    public function index($username = null)
    {
        $sender_id = '';
        $user = '';
        if($username!=''){
            $user = $this->user_m->get_by('username',$username);
            $sender_id = $user->id; 
        }else{
            $sender_id = $this->message_m->get_recent_sender_receiver();
            if($sender_id){
            $user= $this->user_m->get_by('id',$sender_id);
            redirect('/messages/'.$user->username);
            }else{
                redirect('/messages/new');
            }
        }
        $conv = $this->message_m->get_conversation($sender_id);
        $this->template
                ->set('conv', $conv)
                ->set('rec_id', $sender_id)
                ->set('action','')
                ->set('rec', $this->profile_m->get_by('user_id',$user->id))
                ->set('board', $this->load->view('partials/conversation', array('conv' => $conv), true))
                ->build('index');
    }
    
    public function new_message()
    {
        $this->template
                ->set('action', 'new_message')
                ->append_js('module::jquery.coolautosuggest.js')
                ->append_js('module::jquery.coolfieldset.js')
                ->append_css('module::jquery.coolfieldset.css')
                ->append_css('module::jquery.coolautosuggest.css')
                ->set('board', $this->load->view('partials/new_message', array(), true))
                ->build('index');
    }
    public function create()
    {
        if($this->message_m->insert($this->input->post())){
            
            Notify::trigger(Notify::TYPE_MESSAGE, $this->input->post());
            
            $pic        = $this->profile_m->get_profile_pic($this->current_user->id);
            if($this->input->post('action') == 'new_message'){
                exit(json_encode(array('status' => 'redirect', 'to' => $this->input->post('to'))));
            }else{
                return $this->template->build_json(array(
                        'message' => load_view('messages','partials/sender_quote', array(
                            'message' => xss_clean($this->input->post('message')),
                            'pic'     => $pic
                                ), true),
                        'status'  => 'success'
                    ));
            }
            
        }else{
            exit(json_encode(array('status' => 'failure')));
        }
    }
    public function edit()
    {
        
    }
    public function view()
    {
        
    }
    public function trash()
    {
        
    }
    
    public function search()
    {
        //@Todo: every time the db hit is occured ; can be reduced through memcache or any other caching technology. 
        $this->load->model('friend/friend_m');
        $friends = $this->friend_m->get_friends($this->current_user->id);
        $q = $this->input->get('term');
        
        $friend_array = array();
        foreach($friends as $friend){
            $friend_array[$friend->username]= $friend->id;
        }
        $result = array();
        foreach ( $friend_array as $key => $value ) {
            if ( strpos(strtolower($key), $q) !== false ) {
                array_push($result, array( "id" => $value, "label" => $key, "value" => strip_tags($key) ));
            }
            if ( count($result) > 11 )
                break;
        }

        echo json_encode($result); exit; 
    }
    
    public function search2()
    { 
        $this->load->model('friend/friend_m');
        $friends = $this->friend_m->get_friends($this->current_user->id);
        $q = $this->input->get('term');
        $friend_array = array();
        $result = array();
        foreach($friends as $friend){
            $friend_array[$friend->username]= $friend->id;
            $result[]=array("id" => $friend->id, "data" => $friend->display_name, "thumbnail" => '/files/thumb/'.$friend->profile_pic.'/70/70/fit', "description" => $friend->username);
             if ( count($result) > 11 )
                break;
        }

        echo json_encode($result); exit; 
    }
    
}
