<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Album extends Public_Controller
{

    public function __construct()
    {
        parent::__construct();
        if ( !is_logged_in() ) {
            $this->session->set_userdata('redirect_to', current_url());
            redirect('users/login');
        }
        $this->load->library('files/files');
        $this->load->library('ion_auth');
        $this->load->model('album_m');
        
    }

    public function index()
    {
			$this->template
                                ->build('album/form');
        
    }
    
    public function images($album_id, $user_id = null)
    {
        if($user_id==''){
            $user_id = $this->current_user->id; 
        }
        $images = $this->album_m->get_image_files($user_id, $album_id);
        
        $this->template
                ->set_layout(FALSE)
                ->set('images', $images)
                ->build('album/album_images');
    }

    public function create($event_id = null)
    {	
        if($this->input->is_ajax_request()){
            $this->template->set_layout(false);
        }
		$this->form_validation->set_rules('album_name', 'Album Name', 'required|trim|xss_clean|max_length[100]');			
		$this->form_validation->set_rules('description', 'Description', 'trim|xss_clean');			
		$this->form_validation->set_rules('privacy', 'Privacy', "required|trim|xss_clean");
	
		if ($this->form_validation->run() == FALSE) // validation hasn't been passed
		{           
                        if($this->input->post() && $this->input->is_ajax_request()){
                            $response['status'] = false;
                            $response['message'] = validation_errors('<p class="error-msg"><label>','</label></p>');
                            exit(json_encode($response));   
                        }
			$this->template
                                ->set('event_id' , $event_id)
                                ->build('album/form');
		}
		else // passed validation proceed to post success logic
		{
		 	// build array for the model
			
			$form_data = array(
					       	'album_name' => set_value('album_name'),
					       	'privacy' => set_value('privacy')
						);
                        $this->session->set_userdata('album_id',null);
                        $album     = Files::create_album(0, set_value('album_name'),$this->input->post('event_id'), true, set_value('privacy'));
                        $this->session->set_userdata('album_id', $album['data']['id']);
                        exit(json_encode($album));   
		}
        
    }

    public function edit()
    {
        
    }

    public function delete()
    {
        $album_id = $this->input->get('album_id');
        $status = 'failure';
        if(!empty($album_id)) {
            $this->album_m->remove_album($album_id); 
            $status = 'success';
        }
        
        $this->template
                ->build_json(array('status' => $status));
    }

}
