<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Photo extends Public_Controller
{

    private $_image_data = array();

    public function __construct()
    {
        parent::__construct();
        if (!is_logged_in()) {
            $this->session->set_userdata('redirect_to', current_url());
            redirect('users/login');
        }
        $this->load->library('files/files');
        $this->load->library('ion_auth');
        $this->load->model('album_m');
        if ($this->input->is_ajax_request()) {
            $this->template
                    ->set_layout(false);
        }
    }

    private function _settings($username)
    {
        $this->template->set_layout('user');
        if ($this->input->is_ajax_request()) {
            $this->template
                    ->set_layout(false);
        }
        // work out the visibility setting
        switch (Settings::get('profile_visibility')) {
            case 'public':
                // if it's public then we don't care about anything
                break;

            case 'owner':
                // they have to be logged in so we know if they're the owner
                $this->current_user or redirect('users/login/users/view/' . $username);

                // do we have a match?
                $this->current_user->username !== $username and redirect('404');
                break;

            case 'hidden':
                // if it's hidden then nobody gets it
                redirect('404');
                break;

            case 'member':
                // anybody can see it if they're logged in
                $this->current_user or redirect('users/login/users/view/' . $username);
                break;
        }

        // Don't make a 2nd db call if the user profile is the same as the logged in user
        if ($this->current_user && $username === $this->current_user->username) {
            $user = $this->current_user;
        }
        // Fine, just grab the user from the DB
        else {
            $user = $this->ion_auth->get_user($username);
        }

        // No user? Show a 404 error
        $user or show_404();

        $this->template->append_metadata('<script> var current_user = "' . $user->username . '"</script>')
                ->append_js('module::user.js')
                ->append_js('module::album.js')
                ->append_js('wall.js')
                ->set('username', $user->username);
        //->set('random_photos',$this->load->view('profile/random_photos', array('photos' => $this->album_m->get_image_files($user->id)), true));

        return $user;
    }

    public function index($username = null)
    {
        $user   = $this->_settings($username);
        $images = $this->album_m->get_image_files($user->id);
        $this->template
                ->set('images', $images)
                ->build('photo/images');
    }

    public function upload()
    {
        $album_id = $this->input->get('album_id') ? $this->input->get('album_id') : $this->session->userdata('album_id');
        $album_id = !empty($album_id) ? $album_id : 0;
        if ($this->input->is_ajax_request() and $this->input->post()) {
                $result         = Files::upload($album_id, false, 'myfile');
                $result['html'] = load_view('users', 'photo/image', array('result' => $result));
                echo json_encode($result);
                exit;
        }
        $this->template
                ->set('user', $this->current_user)
                ->set('album_id', $album_id)
                ->build('photo/upload');
    }

    public function delete()
    {
        $photo_id = $this->input->get('photo_id'); 
        $status = 'failure';
        if($photo_id!= '') {
            $this->album_m->remove_photo($photo_id);
            $status = 'success';
        }
        $this->template
                ->build_json(array('status' => $status));
    }
}
