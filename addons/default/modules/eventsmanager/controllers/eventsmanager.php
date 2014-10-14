<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 * The events manager module allows administrators to manage events and to display them to users.
 *
 * @author    Ankit Vishwakarma <ankitvishwakarma@sify.com>
 */
class EventsManager extends Public_Controller
{

    /**
     * Constructor method
     *
     * @author Ankit Vishwakarma <ankitvishwakarma@sify.com>
     * @access public
     * @return void
     */
    public function __construct($slug = null)
    {
        parent::__construct();
        $this->lang->load('eventsmanager');
        if (!is_logged_in()) {
            $this->session->set_userdata('redirect_to', current_url());
            redirect('users/login');
        }
        $this->template->append_metadata(
                "<script>
                var baseurl = '" . base_url() . "';
                //var currenturl = '" . current_url() . "';
            </script>"
        );
        
        $this->template->set_layout('event');
        if ($this->input->is_ajax_request()) {
            $this->template->set_layout(false);
        }
        $this->load->model('files/file_folders_m');
        $this->load->model('event_categories_m');
        $this->load->model('trends/trend_m');
        $this->lang->load('categories');
        $this->lang->load('eventsmanager');
        $this->lang->load('blog');
        Asset::add_path('users', 'system/cms/modules/users/');
        $this->template->append_js('users::album.js');
    }

    private function _set_template_content($slug = null)
    {
        
        $this->load->model('users/album_m');

        $event                      = $this->eventsmanager_m->getBy('slug', $slug);
        
        if($event->published==0 && $this->current_user->id != $event->author){
            show_404(); 
        }
        
        // Get the author
        $event->author_display_name = $this->profile_m->get_profile(array('user_id' => $event->author))->display_name;
        // Get the picture filename
        $picture                    = null;
        if (isset($event->picture_id))
            $picture                    = $this->eventsmanager_m->get_image_file($event->picture_id);
        if (isset($picture))
            $event->picture_filename    = $picture->filename;
        $this->template
                ->title($this->module_details['name'])
                ->append_js('wall.js')
                ->set_breadcrumb(lang('eventsmanager:events_label'), $this->module)
                ->append_css('module::common.css')
                ->set('event', $event);

        $this->_load_comments($event->id);
        $this->_load_trends($event->id);

        $this->template
                ->set_breadcrumb($event->title);
    }

    function index()
    {
        $this->template->set_layout('densealife')->set('_user', $this->current_user)->set('user', $this->current_user);
        $all_events = $this->eventsmanager_m->get_all(array(
            'published = true',
            '(start_date >= \'' . date('Y-m-d H:i') . '\'OR end_date >= \'' . date('Y-m-d H:i') . '\')',
                )
        );

        $events = sortEventsByPeriod($all_events);

        $this->template
                ->title($this->module_details['name'])
                ->set_breadcrumb(lang('eventsmanager:events_label'), $this->module)
                ->set_breadcrumb(lang('eventsmanager:upcoming_events_label'))
                ->append_css('module::frontend_index.css')
                ->append_css('module::common.css')
                ->set('sorted_events', $events)
                ->build('index-old');
    }

    public function event($slug = NULL)
    {
        $event = $this->eventsmanager_m->getBy('slug', $slug);
        if (!empty($event)) {
            // Get the author
            $event->author_display_name = $this->profile_m->get_profile(array('user_id' => $event->author))->display_name;
            // Get the picture filename
            $picture                    = null;
            if (isset($event->picture_id))
                $picture                    = $this->eventsmanager_m->get_image_file($event->picture_id);
            if (isset($picture))
                $event->picture_filename    = $picture->filename;

            $this->template
                    ->set('event', $event);

            if (new Datetime($event->end_date) >= new Datetime())
                $this->template->set_breadcrumb(lang('eventsmanager:upcoming_events_label'), $this->module);
            else
                $this->template->set_breadcrumb(lang('eventsmanager:past_events_label'), $this->module . '/past');

            $comments = $this->_load_comments($event->id);
        }
        $this->template
                ->build('event');
    }

    public function past()
    {
        $past_events = $this->eventsmanager_m->get_all(array(
            'published = true',
            'end_date < \'' . date('Y-m-d H:i') . '\'',
                ), 'desc'
        );

        $events                = array();
        if (!empty($past_events))
            $events["past_events"] = $past_events;

        $this->template
                ->title($this->module_details['name'])
                ->set_breadcrumb(lang('eventsmanager:events_label'), $this->module)
                ->set_breadcrumb(lang('eventsmanager:past_events_label'))
                ->append_css('module::frontend_index.css')
                ->append_css('module::common.css')
                ->set('sorted_events', $events)
                ->build('index');
    }

    public function edit($slug)
    {
        $event              = $this->eventsmanager_m->getBy('slug', $slug);
        if($event->author != $this->current_user->id){
            show_404();
        }
        $type               = 'event';
        if ($event->category_id == 2) {
            $type = 'interest';
        }
        $this->create($type, $slug, 'edit');
    }

    public function create($type = 'event', $slug = null, $action = 'create')
    {
        if(!empty($_GET['flush'])){
            $this->session->set_userdata('recently_created_event', '');
        }
        $this->template->set_layout('densealife')->set('type', $type);

        $this->form_validation->set_rules(Events_Validation::rules());
        if(!empty($slug)) {
            $event = $this->eventsmanager_m->getBy('slug', $slug);
            $this->session->set_userdata('recently_created_event', $event->id);
        }
        if ($this->form_validation->run()) {
            $post                = $this->input->post();
            $post['category_id'] = $this->eventsmanager_m->get_entry_category_id_by_slug($type);
            if ($action == 'edit') {
                if ($this->eventsmanager_m->update($event->id, $post) == true) {
                    $this->session->set_userdata('recently_created_event', $event->id);
                    echo json_encode(array('status' => 'success', 'slug' => $this->input->post('slug')));
                    exit;
                }
            } else {
                if ($id = $this->eventsmanager_m->insert($post)) {
                    $this->session->set_userdata('recently_created_event', $id);
                    echo json_encode(array('status' => 'success', 'slug' => $this->input->post('slug')));
                    exit;
                } else {
                    $this->session->set_flashdata('error', lang('eventsmanager:create_error'));
                }
            }
        } else {
            if (!empty($slug)) {
                $this->session->set_userdata('recently_created_event', $event->id);
            } else {
                $event = new StdClass();
                foreach (Events_Validation::rules() as $key => $field) {
                    $value                  = isset($field['default']) ? set_value($field['field'], $field['default']) : set_value($field['field']);
                    $event->$field['field'] = $value;
                }
            }

            if ($this->input->post()) {
                echo json_encode(array('status' => 'failure', 'message' => validation_errors('<li>', '</li>')));
                exit;
            }
        }

        $sub_categories = array();

        if ($type == 'interest') {
            $cat       = $this->event_categories_m->get_by('slug', 'interest');
            $parent_id = $cat->id;
        } else {
            $cat       = $this->event_categories_m->get_by('slug', 'event');
            $parent_id = $cat->id;
        }
        $categories = $this->event_categories_m
                ->order_by('title')
                ->get_many_by(array('parent_id' => $parent_id));

        foreach ($categories as $pc) {
            $sub_categories[$pc->id] = $pc->title;
        }


        if ($this->session->userdata('recently_created_event') != '') {
            $event_id = $this->session->userdata('recently_created_event');
            $event    = $this->eventsmanager_m->get_by('id', $event_id);
        }
        $this->_load_frontend_form_assets();
        $this->_load_frontend_form_data($event);
        $this->template
                ->title($this->module_details['name'], lang('eventsmanager:new_event_label'))
                ->append_js('module::map.js')
                ->set('type', $type)
                ->set('event', $event)
                ->set('category_id', $parent_id)
                ->set('sub_categories', $sub_categories)
                ->build('form');
    }

    public function save_thumb()
    {
//        $this->session->set_userdata('recently_created_event', '5'); 
        $id = $this->session->userdata('recently_created_event');
        // Check for user permissions
        role_or_die('eventsmanager', 'frontend_editing', 'eventsmanager', lang('eventsmanager:notallowed_frontend_editing'));
        if ($this->session->userdata('recently_created_event') != '') {
            $id    = $this->session->userdata('recently_created_event');
            $event = $this->eventsmanager_m->getBy('id', $id);
            if ($event->author == $this->current_user->id) {
                $this->eventsmanager_m->save_image_as($id, $this->input->post());
                echo json_encode(array('status' => 'success'));
                exit;
            } else {
                show_error('You are not authorized to create thumbnail for this event');
            }
        } else {
            show_404();
        }
    }

    public function save_cp_pos()
    { 
        role_or_die('eventsmanager', 'frontend_editing', 'eventsmanager', lang('eventsmanager:notallowed_frontend_editing'));
        if ($this->input->post('event_id') == '' || $this->input->post('new_cp_pos') == '') {
            show_error('There is some error');
        } else {
            $event = $this->eventsmanager_m->getBy('id', $this->input->post('event_id'));
            if ($event->author == $this->current_user->id) {
                $this->eventsmanager_m->save_cp_pos($event->id, $this->input->post());
                echo json_encode(array('status' => 'success'));
                exit;
            } else {
                show_error('You are not authorized to create thumbnail for this event');
            }
        }
    }

    public function save_cover()
    {
        // Check for user permissions
        role_or_die('eventsmanager', 'frontend_editing', 'eventsmanager', lang('eventsmanager:notallowed_frontend_editing'));
        if ($this->session->userdata('recently_created_event') != '') {
            $id    = $this->session->userdata('recently_created_event');
            $event = $this->eventsmanager_m->getBy('id', $id);
            if ($event->author == $this->current_user->id) {
                $this->eventsmanager_m->save_coverphoto($id, $this->input->post());
                echo json_encode(array('response' => 'success'));
                exit;
            } else {
                show_error('You are not authorized to create thumbnail for this event');
            }
        } else {
            show_404();
        }
    }

    public function edit11($slug)
    {
        // Check for user permissions
        role_or_die('eventsmanager', 'frontend_editing', 'eventsmanager', lang('eventsmanager:notallowed_frontend_editing'));

        $slug or show_404();

        $event = $this->eventsmanager_m->getBy('slug', $slug);
        if (empty($event)) {
            $this->session->set_flashdata('error', lang('eventsmanager:exists_error'));
            redirect('eventsmanager');
        }

        $this->form_validation->set_rules(Events_Validation::rules());

        if ($this->form_validation->run()) {
            if ($this->eventsmanager_m->update($event->id, $this->input->post()) == true) {
                $this->session->set_flashdata('success', lang('eventsmanager:update_success'));

                $this->input->post('btn-action') == 'save_exit' ? redirect('eventsmanager/' . $event->slug) : redirect('eventsmanager/edit/' . $event->slug);
            } else {
                $this->session->set_flashdata('error', lang('eventsmanager:update_error'));
            }
        }


        // Load every required stuff for frontend editing
        $this->_load_frontend_form_data($event);
        $this->_load_frontend_form_assets();

        $this->template
                ->title($this->module_details['name'], lang('eventsmanager:manage_event_label') . ' : "' . $event->title . '"')
                ->set('event', $event)
                ->build('form');
    }

    private function _load_comments($event_id)
    {
        $event = $this->eventsmanager_m->getBy('id', $event_id);
        if (isset($event->enable_comments) or $event->enable_comments) {

            $this->load->library('comments/comments', array(
                'entry_id'    => $event->id,
                'entry_title' => $event->title,
                'module'      => 'eventsmanager',
                'singular'    => 'eventsmanager:event',
                'plural'      => 'eventsmanager:events',
            ));
        }
    }

    private function _load_trends($event_id)
    {
        $event = $this->eventsmanager_m->getBy('id', $event_id);
        if ($event->enable_comments) {

            $this->load->library('trends/trends', array(
                'entry_id'    => $event->id,
                'entry_title' => $event->title,
                'module'      => 'eventsmanager',
                'singular'    => 'eventsmanager:event',
                'plural'      => 'eventsmanager:events',
            ));
        }
    }

    ////////////////////////////////////////////////////////////////
    // Helpers for frontend editing

    private function _load_frontend_form_assets()
    {
        $this->lang->load('buttons');

        // Use Asset library to retrieve easily PyroCMS default admin theme plugins
        Asset::add_path('admin_theme', 'system/cms/themes/pyrocms/');
        Asset::add_path('profile', 'addons/default/modules/profile/');
        Asset::add_path('users', 'system/cms/modules/users/');

        //  Asset::add_path('jcrop', 'assets/jcrop/');
        // Load assets in template
        $this->template
                // CKEditor
                ->append_js('admin_theme::ckeditor/ckeditor.js')
                ->append_js('admin_theme::ckeditor/adapters/jquery.js')
                // Google Maps
                ->append_metadata("<script type='text/javascript' src='http://maps.google.com/maps/api/js?sensor=false&language=" . $this->current_user->lang . "'></script>")
                // jQuery UI (Datepicker)
                ->append_js('module::jquery-ui.min.js')
                ->append_css('admin_theme::jquery/jquery-ui.css')
                // Image Area Select
                ->append_js('module::jquery.imgareaselect.pack.js')
                ->append_css('module::imgareaselect/imgareaselect-default.css')
                // Misc styles and scripts
                ->append_js('module::form.js')
                ->append_js('module::frontend-form.js')
                ->append_css('module::frontend_form.css')
                ->append_js('wall.js')
                ->append_js('profile::profile.js')
                ->append_js('users::album.js')
//                ->append_js('jcrop::jquery.Jcrop.js')
//                ->append_css('jcrop::jquery.Jcrop.css')
                // Variables
                ->set('date_format', $this->settings->get('date_format'))
                ->set('date_interval', $this->settings->get('events_date_interval'));
    }

    private function _load_frontend_form_data($event = null)
    {
        $file_folders = $this->file_folders_m->get_folders(); // Get the folders

        $picture  = null;
        $pictures = array();
        if (isset($event->picture_id))
            $picture  = $this->eventsmanager_m->get_image_file($event->picture_id);
        if (isset($picture))
            $pictures = $this->eventsmanager_m->get_images_files($picture->folder_id); // Loads pictures from selected picture folder
        else {
            $picture     = null;
            $folders_ids = array_keys($file_folders);
            if (isset($folders_ids[0]))
                $pictures    = $this->eventsmanager_m->get_images_files($folders_ids[0]); // Choose the first folder if exists
            else
                $pictures    = array(); // No folder and no image on the site
        }

        $folders_tree = array();
        foreach ($file_folders as $folder) {
            $indent                    = repeater('&raquo; ', $folder->depth);
            $folders_tree[$folder->id] = $indent . $folder->name;
        }

        $this->template
                ->set('pictures', $pictures)
                ->set('picture', $picture)
                ->set('folders', $folders_tree)
                ->set('user', $this->current_user)
                ->set('_user', $this->current_user);
    }

    ////////////////////////////////////////////////////////////////
    // Simple relays for form validation callbacks

    public function slug_check($slug)
    {

        return Events_Validation::slug_check($slug);
    }

    public function date_check($end_date)
    {
        return Events_Validation::date_check($end_date);
    }

    public function wall($slug = null)
    {
        $this->_set_template_content($slug);
        $event         = $this->eventsmanager_m->getBy('slug', $slug);
        $this->load->model('profile/auto_approval_m');
        $auto_approved = (bool) $this->auto_approval_m->count_by(array('admin_id' => $event->author, 'user_id' => $this->current_user->id, 'approval_type' => 'comment', 'status' => 'on'));
        $this->db->set_dbprefix('default_');
        $this->load->model('comments/comment_blacklists_m');
        $blacklisted   = $this->comment_blacklists_m->is_blacklisted($event->author, $this->current_user->id);
        $allow_comment = false;
        $message = null;
        //allow comment if user is admin
        if($this->current_user->group == 'admin' or $this->current_user->id == $event->author or $auto_approved){
            $allow_comment = true;
        } else {
            if($blacklisted) {
              $message = 'You have been blocked by the creator of event <b class="txt-up">' . $event->title. '</b>';
            } elseif(!$this->trend_m->am_i_following($event->id)){
                $message = 'Follow the event <b class="txt-up">'. $event->title. '</b> to get updates, post and comment';
            } else {
                $allow_comment = true;
            }
            
            if($event->post_permission=='CREATER'){
                $message = '';
                $allow_comment = false; 
            }
        }
       
        
        $this->template
                ->set('event', $event)
                ->set('allow_comment', $allow_comment)
                ->set('blacklisted', $blacklisted)
                ->set('message', $message)
                ->build('eventsmanager/wall');

    }

    public function about($slug = null)
    {
        $this->_set_template_content($slug);
        if(!empty($slug)){
            $event = $this->eventsmanager_m->getBy('slug', $slug);
        }
        $this->template
               ->append_metadata("<script type='text/javascript' src='http://maps.google.com/maps/api/js?sensor=false&language=" . $this->current_user->lang . "'></script>")
                ->append_metadata("<script type='text/javascript'>var MAP_PLACE = '".$event->place."'; var CURRENT_LANGUAGE = 'en';</script>")
                ->append_js('module::map.js')
                ->build('eventsmanager/about');
    }

    public function albums($slug = null)
    {
        $this->_set_template_content($slug);
        $event = new stdClass();
        if(!empty($slug)) {
            $event  = $this->eventsmanager_m->getBy('slug', $slug);   
        }
        $albums = $this->album_m->get_albums(null, $event->id);
        
        foreach ($albums['data']['folder'] as &$album) {
            if ($album->file_count != 0) {
                $cover              = $this->eventsmanager_m->get_images_files($album->id);
                $album_cover        = current($cover);
                $album->folder_image = is_object($album_cover) ? $album_cover->id: '';
            }
        }
        $user_uploads = $this->eventsmanager_m->get_user_uploads_by_event_id($event->id);
        $this->template
                ->set('albums', $albums)
                ->set('photos', $user_uploads)
                ->set('event', $event)
                ->build('eventsmanager/albums');
    }

    public function videos($slug = null)
    {

        $this->_set_template_content($slug);
        $event          = $this->eventsmanager_m->getBy('slug', $slug);
        $youtube_videos = unserialize($event->youtube_videos);

        $albums            = $this->file_folders_m->get_albums($event->id);
        $count_video_files = 0;
        foreach ($albums as &$album) {
            $video_files = $this->eventsmanager_m->get_files('v', $album->id);
            if (!empty($video_files)) {
                $album->videos = $video_files;
                $count_video_files ++;
            } else {
                $album->videos = '';
            }
        }

        $this->template
                ->set('albums', $albums)
                ->set('count', $count_video_files)
                ->set('youtube_videos', $youtube_videos)
                ->build('eventsmanager/videos');
    }

    public function followers($slug = null)
    {
        $this->_set_template_content($slug);
        $event     = $this->eventsmanager_m->getBy('slug', $slug);
        $this->load->library('trends/Trends');
        $followers = $this->trends->get_followers($event->id);

        $this->load->library('friend/friend');

        $this->template
                ->set('followers', $followers)
                ->build('eventsmanager/followers');
    }

    public function ajax_album_images($album_id)
    {
        $images = $this->eventsmanager_m->get_images_files($album_id); // Loads pictures from selected picture folder
        $this->template
                ->set_layout(FALSE)
                ->set('images', $images)
                ->build('album_images');
    }

    public function upload_wall_status()
    {
        $this->load->library('files/files');
        $result         = null;
        $input          = $this->input->post();
        $folder_content = Files::folder_contents(0, $input['entry_id'], true);
        $folder_id      = null;
        if (empty($folder_content['data']['folder'])) {
            $album     = Files::create_album(0, 'User Uploads :' . ucfirst($input['title']), $input['entry_id'], true);
            $folder_id = $album['data']['id'];
        } else {
            $folder    = reset($folder_content['data']['folder']);
            $folder_id = $folder->id;
        }
        if ($folder_id) {
            //$result = Files::upload($input['folder_id'], $input['name'], 'file', $input['width'], $input['height'], $input['ratio'], null, $input['alt_attribute']);
            $result = Files::upload($folder_id, false, 'file');
            $result['status'] AND Events::trigger('file_uploaded', $result['data']);
        }

        ob_end_flush();
        $this->template
                ->set_layout(FALSE)
                ->set('result', $result)
                ->set('encoded', $this->encrypt->encode(serialize($result)))
                ->build('wall_status_media');
    }

    protected function load_view($view, $data = null)
    {
        $ext = pathinfo($view, PATHINFO_EXTENSION) ? '' : '.php';

        if (file_exists($this->template->get_views_path() . 'modules/eventsmanager/' . $view . $ext)) {
            // look in the theme for overloaded views
            $path = $this->template->get_views_path() . 'modules/eventsmanager/';
        } else {
            // or look in the module
            list($path, $view) = Modules::find($view, 'eventsmanager', 'views/');
        }

        // add this view location to the array
        $this->load->set_view_path($path);
        $this->load->vars($data);

        return $this->load->_ci_load(array('_ci_view' => $view, '_ci_return' => true));
    }

    public function get_sub_categories()
    {
        $cat_id            = $this->input->post('cat_id', '');
        $parent_categories = $this->event_categories_m->get_sub_categories($cat_id);
        $parents           = array();
        foreach ($parent_categories as $pc) {
            $parents[$pc->id] = $pc->title;
        }
        // exit(form_dropdown('sub_category_id', array( '' => lang('cat:no_category_select_label') ) + $parents,'class="drpdwn_sub_category_id"'));
        exit(json_encode($parents));
    }

    public function ajax_search_events($title)
    {
        $events = $this->eventsmanager_m->like('title', $title)->limit(10)->get_many_by(array('published' => 1));

        $this->template
                ->set_layout(false)
                ->set('term', $title)
                ->set('events', $events)
                ->build('search');
    }

    public function add_friend($event_slug = null)
    {

        $friends                       = $this->friend_m->get_friends($this->current_user->id);
        $notifications                 = $this->notification_m->get_by(array('type' => Notify::TYPE_INVITE, 'sender_id' => $this->current_user->id));
        $friends_invititation_not_sent = array();
        foreach ($friends as $friend) {

            if (isset($notifications->rec_id) && $friend->user_id == $notifications->rec_id) {
                continue;
            }
            $friends_invititation_not_sent[] = $friend;
        }

        if ($event_slug) {
            $event = $this->eventsmanager_m->get_by('slug', $event_slug);
            $event or show_404();
        }
        if ($this->input->post()) {
            $data       = $this->input->post('data');
            $invitation = unserialize($this->encrypt->decode($data));

            Notify::trigger(Notify::TYPE_INVITE, $invitation);

            // Email 
            if (array_key_exists('event', $invitation)
                    and $event = $this->eventsmanager_m->get_by('id', $invitation['event'])) {
                // Add in some extra details
                $content['slug'] = 'invite-friend-to-event';

                $content['username']   = $this->current_user->username;
                $friend                = $this->user_m->get_by('id', $invitation['rec_id']);
                $content['to']         = $friend->email;
                $content['event_link'] = '/eventsmanager/' . $event->slug;
                @Events::trigger('email', $event);
            }
            echo json_encode(array(
                'status'   => 'success',
                'response' => array(
                    'msg'       => 'Invitation sent successfully',
                    'friend_id' => $invitation['rec_id']
                )
            ));
            exit;
        }
        $this->template
                ->set_layout(false)
                ->set('event', $event)
                ->set('friends_count', count($friends))
                ->set('friends', $friends_invititation_not_sent)
                ->build('add_friend');
    }

    public function invite_by_mail($event_slug = null)
    {
        $event = '';
        if ($event_slug != '') {
            $event = $this->eventsmanager_m->get_by('slug', $event_slug);
            $event or show_404();
        }
        if ($friends = $this->input->post('friends')) {
            $friends = explode(',', $friends);

            $event          = $this->eventsmanager_m->get_by('slug', $event_slug);
            $invalid_emails = array();
            foreach ($friends as $friend_email) {
                if (filter_var(trim($friend_email), FILTER_VALIDATE_EMAIL)) {
                    // Add in some extra details
                    $content['slug'] = 'invite-friend-to-event-by-mail';

                    $content['username']   = $this->current_user->username;
                    $content['to']         = $friend_email;
                    $content['event_link'] = '/eventsmanager/' . $event->slug;
                    @Events::trigger('email', $content);
                } else {
                    $invalid_emails[] = trim($friend_email);
                }
            }
            if (count($invalid_emails)) {
                exit(json_encode(array('status' => 'error', 'msg' => 'Please enter valid email ids', 'invalid_emails' => implode(',', $invalid_emails))));
            } else {
                exit(json_encode(array('status' => 'success', 'msg' => 'Email sent successfully')));
            }
        }
        $this->template
                ->set_layout(false)
                ->set('event', $event)
                ->build('invite_by_mail');
    }

}
