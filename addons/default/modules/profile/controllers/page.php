<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends Public_Controller
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
        $this->load->library('comments/comments');
        $this->load->library('users/Ion_auth');
        $this->load->library('trends/Trends');
        $this->load->library('eventsmanager/Events_Lib');
        $this->load->model(array('eventsmanager_m','friend/friend_m'));
        if($this->uri->segment(1) =='densealife-page'){
            $username = $this->uri->segment(2, $this->current_user->username);
            $user = $this->ion_auth->get_users_by_username($username);
            if(empty($user)){
                show_404();
            }else{
                $this->user = current($user);
                $this->page_location = '/densealife-page/'.$this->user->username;
                $this->template
                        ->append_metadata('<script> var page_location = "'. $this->page_location.'"</script>')
                        ->set('_user', $this->user);
                
            }
        }
        $friends = $this->friend_m->get_friends($this->user->id);
        
        $this->template
                ->set_layout('densealife')
                ->set('user',$this->user)
                ->set('friends',$friends)
                ->append_js('wall.js')
                ->append_js('module::profile.js');
        if($this->input->is_ajax_request()){
            $this->template
                ->set_layout(false);
        }
        
    }

    public function index()
    { 
        $this->template
                ->set('content', $this->load_view('page/activity'))
                ->build('page/index');
    }
    
    public function events()
    {       
            $trendings = $this->trends->get_trending($this->user->id, 3);
            
            $favorites = $this->trends->get_favorites($this->user->id, 3);
            
            $upcomings = $this->eventsmanager_m->get_upcoming($this->user->id, 3);
            
            $this->template
                ->set('content', $this->load_view('page/events', array(
                                                                        'trendings' => $trendings, 
                                                                        'favorites' => $favorites,
                                                                        'upcomings' => $upcomings
                                                                )
                        ))
                ->build('page/index');
    }
    
    public function trending()
    {
        $trendings = $this->trends->get_trending($this->user->id);
            $this->template
                ->set('content', $this->load_view('page/trending', array('trendings' => $trendings)))
                ->build('page/index');
    }
    
    public function upcoming()
    {
        $upcomings = $this->eventsmanager_m->get_upcoming($this->user->id);
         
            $this->template
                ->set('content', $this->load_view('page/upcoming',array('upcomings' => $upcomings)))
                ->build('page/index');
    }
    
    public function favorite()
    {
        $favorites = $this->trends->get_favorites($this->user->id);
        $this->template
                ->set('content', $this->load_view('page/favorite', array('favorites' => $favorites)))
                ->build('page/index');
    }
    
    public function find_friends()
    {
            $this->template
                ->set('content', $this->load_view('page/find_friends'))
                ->build('page/index');
    }
    

    protected function load_view($view, $data = null)
    {
        $ext = pathinfo($view, PATHINFO_EXTENSION) ? '' : '.php'; 

        if ( file_exists($this->template->get_views_path() . 'modules/profile/' . $view . $ext) ) {
            // look in the theme for overloaded views
            $path = $this->template->get_views_path() . 'modules/profile/';
        } else {
            // or look in the module
            list($path, $view) = Modules::find($view, 'profile', 'views/');
        }

        // add this view location to the array
        $this->load->set_view_path($path);
        $this->load->vars($data);

        return $this->load->_ci_load(array( '_ci_view' => $view, '_ci_return' => true ));
    }

}
