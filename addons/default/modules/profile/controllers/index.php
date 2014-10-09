<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends Public_Controller
{

    public $user;
    public $page_location;

    public function __construct()
    {
        parent::__construct();

        if (!is_logged_in()) {
            $this->session->set_userdata('redirect_to', current_url());
            redirect('users/login');
        }
        $this->template->append_metadata(
                "<script>
                                                var baseurl = '" . base_url() . "'
                                             </script>"
        );
        $this->load->library('comments/comments');
        $this->load->library('users/Ion_auth');
        $this->load->library('trends/Trends');
        $this->load->library(array('eventsmanager/Events_Lib', 'eventsmanager/Events_Validation'));
        $this->load->model(array('eventsmanager_m', 'friend/friend_m'));
        $this->load->model('files/file_folders_m');
        $this->lang->load('eventsmanager/eventsmanager');
        $this->template->set('_user', $this->current_user);

        $friends    = $this->friend_m->get_friends($this->current_user->id);
        $this->user = $this->current_user;

        Asset::add_path('admin_theme', 'system/cms/themes/pyrocms/');
        $this->template
                ->set_layout('densealife')
                ->set('friends', $friends)
                ->append_js('wall.js')
                ->append_js('module::profile.js');
        if ($this->input->is_ajax_request()) {
            $this->template
                    ->set_layout(false);
        }
    }

    public function index()
    {
        $this->template
                ->set('content', load_view('profile', 'index/activity', array('user' => $this->user)))
                ->build('index/index');
    }

    public function events($type = 'event', $sub_cat_slug = null)
    {
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

        $sub_categories = array();
        $sub_cat_ids    = array();

        foreach ($categories as $pc) {
            $sub_categories[$pc->slug] = $pc->title;
            $sub_cat_ids[$pc->slug]    = $pc->id;
        }
        $sub_cat_id = null;

        if ($sub_cat_slug != '' && array_key_exists($sub_cat_slug, $sub_cat_ids)) {

            $sub_cat_id = $sub_cat_ids[$sub_cat_slug];
        }
        $trendings = $this->trends->get_trending('', $type, $sub_cat_id, 3);

        $favorites = $this->trends->get_favorites('', $type, $sub_cat_id, 3);

        $upcomings = array();
        $populars  = array();

        if ($type == 'event') {
            $upcomings = $this->eventsmanager_m->get_upcoming('', $type, $sub_cat_id, 3);
        }

        if ($type == 'interest') {
            $populars = $this->trends->get_trending('', $type, $sub_cat_id, 3);
        }

        $this->template
                ->set('content', load_view('profile', 'index/events', array(
                    'trendings'      => $trendings,
                    'favorites'      => $favorites,
                    'upcomings'      => $upcomings,
                    'populars'       => $populars,
                    'user'           => $this->user,
                    'type'           => $type,
                    'sub_categories' => $sub_categories,
                                )
                ))
                ->build('index/index');
    }

    public function trending($type = 'event')
    {
        $this->template->append_metadata("<script>var ENTRY_TYPE = '" . $type . "'</script>");
        $trendings = $this->trends->get_trending('', $type);
        $this->template
                ->set('content', load_view(
                                'profile', 'index/trending', array(
                    'trendings' => $trendings,
                    'user'      => $this->user,
                    'type'      => $type,
                                )
                ))
                ->build('index/index');
    }

    public function upcoming($type = 'event')
    {
        $upcomings = $this->eventsmanager_m->get_upcoming('', $type);

        $this->template
                ->set('content', load_view(
                                'profile', 'index/upcoming', array(
                    'upcomings' => $upcomings,
                    'user'      => $this->user
                                )
                ))
                ->build('index/index');
    }

    public function favorite($type = 'event')
    {
        $favorites = $this->trends->get_favorites('', $type);
        $this->template
                ->set('content', load_view(
                                'profile', 'index/favorite', array(
                    'favorites' => $favorites,
                    'user'      => $this->user
                                )
                ))
                ->build('index/index');
    }

    public function thumbnail($event_id = null)
    {
        $user_id    = $this->current_user->id;
        $event_id   = $this->session->userdata('recently_created_event');
        
        if ($event_id != '') {
            $event        = $this->eventsmanager_m->getBy('id', $event_id);
            $this->load->model('users/album_m');
            $album_images = array();
            $albums       = $this->album_m->get_albums($user_id, $event_id);
            foreach ($albums['data']['folder'] as $images) {
                foreach ($images->content as $image) {
                    $album_images[] = $image;
                }
            }
            echo load_view('profile', 'index/partials/form_picture', array(
                            'pictures' => $album_images,
                            'event'    => $event
                        )
                );
        } else {
            echo 'No Event specified';
        }
    }

    public function album($slug = null)
    {   
        $recently_created_event = $this->session->userdata('recently_created_event');
        $event    = new stdClass();
        $this->load->model('users/album_m');
        $event_id = null;
        if(empty($slug) and empty($recently_created_event)) {
            exit('No event specified'); 
        } elseif (!empty($recently_created_event)) {
            $event    = $this->eventsmanager_m->getBy('id', $recently_created_event);
            $event_id = $recently_created_event;
        } elseif (!empty($slug)) {
            $event    = $this->eventsmanager_m->getBy('slug', $slug);
            $event_id = $event->id;
        }
        $albums = $this->album_m->get_albums($this->current_user->id, $event_id);
        $this->template
                ->set('_user', $this->current_user)
                ->set('event', $event)
                ->set('albums', $albums)
                ->set('showDir', true)
                ->build('eventsmanager/partials/form_album');
        
    }

    private function _load_frontend_form_assets()
    {
        $this->lang->load('buttons');

        // Use Asset library to retrieve easily PyroCMS default admin theme plugins
        Asset::add_path('mod_assets', 'addons/default/modules/eventsmanager/');
        Asset::add_path('admin_theme', 'system/cms/themes/pyrocms/');

        // Load assets in template
        $this->template
                // CKEditor
                ->append_metadata("<script type='text/javascript' src='http://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.0.1/ckeditor.js'></script>")
//                ->append_js('admin_theme::ckeditor/ckeditor.js')
                ->append_js('admin_theme::ckeditor/adapters/jquery.js')
                // Google Maps
                ->append_metadata("<script type='text/javascript' src='http://maps.google.com/maps/api/js?sensor=false&language=" . $this->current_user->lang . "'></script>")
                // jQuery UI (Datepicker)
                ->append_js('mod_assets::jquery-ui.min.js')
                ->append_css('admin_theme::jquery/jquery-ui.css')
                // Image Area Select
                ->append_js('mod_assets::jquery.imgareaselect.pack.js')
                ->append_css('mod_assets::imgareaselect/imgareaselect-default.css')
                // Misc styles and scripts
                ->append_js('mod_assets::form.js')
                ->append_js('mod_assets::frontend-form.js')
                ->append_css('mod_assets::frontend_form.css')

                // Variables
                ->set('date_format', $this->settings->get('date_format'))
                ->set('date_interval', $this->settings->get('events_date_interval'));
    }

    private function _load_frontend_form_data($event = null)
    {
        $this->load->library('files/files');
        $file_folders = Files::folder_contents(0, null, true, $this->current_user->id);
        $picture      = null;
        $pictures     = array();
        if (isset($event->picture_id))
            $picture      = $this->eventsmanager_m->get_image_file($event->picture_id);
        if (isset($picture))
            $pictures     = $this->eventsmanager_m->get_images_files($picture->folder_id); // Loads pictures from selected picture folder
        else {
            $picture = null;

            foreach ($file_folders['data']['folder'] as $folder) {
                if ($folder->file_count >= 1) {
                    $pictures = $folder->content;
                    break;
                }
            }
        }

        $folders_tree = array();
        foreach ($file_folders['data']['folder'] as $folder) {
            $folders_tree[$folder->id] = $folder->name;
        }

        $obj           = new stdClass();
        $obj->pictures = $pictures;
        $obj->picture  = $picture;
        $obj->folders  = $folders_tree;
        return $obj;
    }

    public function streams($titlelike = null)
    {
        $object = $this->eventsmanager_m;
        if ($titlelike != '') {
            $object->like('title', $titlelike);
        }
        $events = $object->get_many_by(array('published' => 1));
        $this->template
                ->set('user', $this->user)
                ->set('events', $events)
                ->build('index/streams');
    }

}
