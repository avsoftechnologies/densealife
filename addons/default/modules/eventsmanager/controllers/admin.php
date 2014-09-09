<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin extends Admin_Controller
{

    /**
     * Validation rules for creating a new event
     *
     * @var array
     * @access private
     */
    protected $section        = "events_list";
    private $validation_rules = array(
        'category_id'     => array(
            'field' => 'category_id',
            'label' => 'lang:cat:category_label',
            'rules' => 'required'
        ),
        'sub_category_id' => array(
            'field' => 'sub_category_id',
            'label' => 'lang:sub_cat:category_label',
            'rules' => 'required'
        ),
        'title'           => array(
            'field' => 'title',
            'label' => 'lang:eventsmanager:title_label',
            'rules' => 'trim|max_length[300]|required'
        ),
        'slug'            => array(
            'field' => 'slug',
            'label' => 'lang:eventsmanager:slug_label',
            'rules' => 'trim|required|callback_slug_check'
        ),
        'website' => array(
            'field' => 'website',
            'label' => 'Website',
            'rules' => 'trim|valid_url|xss_clean'
        ),
        'affiliations' => array(
            'field' => 'affiliations',
            'label' => 'Affiliations',
            'rules' => 'trim|max_length[300]'
        ),
        'about'        => array(
            'field' => 'about',
            'label' => 'About',
            'rules' => 'trim|required'
        ),
        'description'  => array(
            'field' => 'description',
            'label' => 'lang:eventsmanager:description_label',
            'rules' => 'trim|required'
        ),
        'place'        => array(
            'field' => 'place',
            'label' => 'lang:eventsmanager:place_label',
            'rules' => 'trim'
        ),
        'start_date'   => array(
            'field' => 'start_date',
            'label' => 'lang:eventsmanager:date_label',
            'rules' => 'trim'
        ),
        'end_date'     => array(
            'field' => 'end_date',
            'label' => 'lang:eventsmanager:end_date_label',
            //'rules' => 'trim|callback_date_check'
            'rules' => 'trim|callback_date_check'
        )
            );

    /*     * ************************************
     * Form validation callbacks
     * *********************************** */

    public function slug_check($slug)
    {
        if ($slug == 'past') {
            $this->form_validation->set_message('slug_check', lang('eventsmanager:slug_past_error'));
            return false;
        }
        return true;
    }

    public function date_check($end_date)
    {
        $input = $this->input->post();
        if (!empty($input['start_date'])) {
            // Format the dates
            $DATE_FORMAT    = $this->settings->get('date_format');
            $start_datetime = date_create_from_format($DATE_FORMAT, $input['start_date']);
            $start_datetime->setTime($input['start_time_hour'], $input['start_time_minute']);
            if ($input['end_date_defined']) {
                $end_datetime = date_create_from_format($DATE_FORMAT, $input['end_date']);
                $end_datetime->setTime($input['end_time_hour'], $input['end_time_minute']);
            }
            if ($input['end_date_defined'] && strtotime($end_datetime->format('Y-m-d')) < strtotime($start_datetime->format('Y-m-d'))) { // If end date is prior to start date
                $this->form_validation->set_message('date_check', lang('eventsmanager:date_logic_error'));
                return false;
            }
        }
        return true;
    }

    /**
     * Constructor method
     *
     * @author Ankit Vishwakarma <ankitvishwakarma@sify.com>
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->config->load('albums');
        $this->lang->load('albums');
        $this->load->library(array('files/files', 'events_lib'));

        $allowed_extensions = array();
        foreach (config_item('files:allowed_file_ext') as $type) {
            $allowed_extensions = array_merge($allowed_extensions, $type);
        }

        $this->template->append_metadata(
                "<script>
				pyro.lang.fetching = '" . lang('albums:fetching') . "';
				pyro.lang.fetch_completed = '" . lang('albums:fetch_completed') . "';
				pyro.lang.start = '" . lang('albums:start') . "';
				pyro.lang.width = '" . lang('albums:width') . "';
				pyro.lang.height = '" . lang('albums:height') . "';
				pyro.lang.ratio = '" . lang('albums:ratio') . "';
				pyro.lang.full_size = '" . lang('albums:full_size') . "';
				pyro.lang.cancel = '" . lang('buttons:cancel') . "';
				pyro.lang.synchronization_started = '" . lang('albums:synchronization_started') . "';
				pyro.lang.untitled_album = '" . lang('albums:untitled_album') . "';
				pyro.lang.exceeds_server_setting = '" . lang('albums:exceeds_server_setting') . "';
				pyro.lang.exceeds_allowed = '" . lang('albums:exceeds_allowed') . "';
				pyro.files = { permissions : " . json_encode(Files::allowed_actions()) . " };
				pyro.files.max_size_possible = '" . Files::$max_size_possible . "';
				pyro.files.max_size_allowed = '" . Files::$max_size_allowed . "';
				pyro.files.valid_extensions = '" . implode('|', $allowed_extensions) . "';
				pyro.lang.file_type_not_allowed = '" . addslashes(lang('albums:file_type_not_allowed')) . "';
				pyro.lang.new_album_name = '" . addslashes(lang('albums:new_album_name')) . "';
				pyro.lang.alt_attribute = '" . addslashes(lang('albums:alt_attribute')) . "';
                                current_user = '" . $this->current_user->id . "';
				// deprecated
				pyro.files.initial_folder_contents = " . (int) $this->session->flashdata('initial_folder_contents') . ";
			</script>");

        $this->load->model(array('eventsmanager_m', 'event_categories_m'));
        $this->lang->load('categories');
        $this->lang->load('eventsmanager');
        $this->lang->load('blog');
        $this->load->helper('html');
        $this->load->helper('MY_words');
        $this->load->helper('MY_date');
        $this->load->helper('MY_crop');
        $this->load->model('files/file_folders_m');
        // $this->validation_rules['start_date']['default'] = date("Y-m-d H:i") ;


        $this->load->library(array('keywords/keywords', 'form_validation'));

        $_categories = array();
        if ($categories  = $this->event_categories_m->get_parent_categories()) {
            foreach ($categories as $category) {
                $_categories[$category->id] = $category->title;
            }
        }


        $this->template
                ->set('categories', $_categories);
    }

    public function index()
    {
        $events = $this->eventsmanager_m->order_by('published_at','DESC')->order_by('start_date','DESC')->get_all();
        $this->template
                ->append_css('module::admin.css')
                ->set('events', $events)
                ->build('admin/index');
    }

    public function create()
    {
        $this->form_validation->set_rules($this->validation_rules);
        if ($this->form_validation->run()) {
            if ($id = $this->eventsmanager_m->insert($this->input->post())) {
                // Everything went ok...
                $this->session->set_flashdata('success', lang('eventsmanager:create_success'));

                // Redirect back to the form or main page
                $this->input->post('btnAction') == 'save_exit' ? redirect('admin/eventsmanager') : redirect('admin/eventsmanager/manage/' . $id);
            }
            // Something went wrong..
            else {
                $this->session->set_flashdata('error', lang('eventsmanager:create_error'));
                redirect('admin/eventsmanager/create');
            }
        } else {
            $event = new StdClass();

            foreach ($this->validation_rules as $key => $field) {
                $value                  = isset($field['default']) ? set_value($field['field'], $field['default']) : set_value($field['field']);
                $event->$field['field'] = $value;
            }
        }

        $file_folders = $this->file_folders_m->get_folders();
        $folders_tree = array();
        foreach ($file_folders as $folder) {
            $indent                    = repeater('&raquo; ', $folder->depth);
            $folders_tree[$folder->id] = $indent . $folder->name;
        }

        $folders_ids = array_keys($file_folders);
        if (isset($folders_ids[0]))
            $pictures    = $this->eventsmanager_m->get_images_files($folders_ids[0]);
        else
            $pictures    = array();

        $parent_categories = $this->event_categories_m
                ->order_by('title')
                ->get_many_by(array('parent_id' => '0', 'status' => '1'));

        $parents = array();

        foreach ($parent_categories as $pc) {
            $parents[$pc->id] = $pc->title;
        }
        $data['parent_categories'] = $parents;
        $sub_categories            = array();

        $this->template
                ->title($this->module_details['name'], lang('eventsmanager:new_event_label'))
                ->append_metadata($this->load->view('fragments/wysiwyg', array(), true))
                ->append_js('module::event_form.js')
                ->append_js('module::event_category_form.js')
                ->append_js('module::form.js')
                ->append_js('module::jquery.ui.datepicker-fr.js')
                ->append_js('module::jquery.imgareaselect.pack.js')
                ->append_css('module::admin.css')
                ->append_css('module::coverphoto.css')
                ->append_js('module::coverphoto.js')
                ->append_css('module::imgareaselect/imgareaselect-default.css')
                ->append_metadata("<script type='text/javascript' src='http://maps.google.com/maps/api/js?sensor=false&language=" . $this->current_user->lang . "'></script>")
                ->set('event', $event)
                ->set('pictures', $pictures)
                ->set('folders', $folders_tree)
                ->set('date_format', $this->settings->get('date_format'))
                ->set('date_interval', $this->settings->get('events_date_interval'))
                ->set('categories', $parents)
                ->set('sub_categories', $sub_categories)
                ->build('admin/form');
    }

    /**
     *
     * @author Ankit Vishwakarma <ankitvishwakarma@sify.com>
     * @access public
     * @param int $id 
     * @return void
     */
    public function manage($id)
    {
        $this->template
                ->title($this->module_details['name'])
                ->append_css('jquery/jquery.tagsinput.css')
                ->append_css('module::jquery.fileupload-ui.css')
                ->append_js('module::event_form.js')
                ->append_css('module::album.css')
                ->append_js('jquery/jquery.tagsinput.js')
                ->append_js('module::jquery.fileupload.js')
                ->append_js('module::jquery.fileupload-ui.js')
                ->append_js('module::functions.js')
                // should we show the "no data" message to them?
                ->set('albums', (bool) $this->file_folders_m->count_by(array('parent_id' => 0, 'event_id' => $id)))
                ->set('locations', array_combine(Files::$providers, Files::$providers))
                ->set('album_tree', Files::folder_tree($id));
        $this->template->append_metadata(
                "<script>
                            event_id = '" . $id . "';
                      </script>"
        );
        $this->form_validation->set_rules($this->validation_rules);

        $event = $this->eventsmanager_m->getBy('id', $id);

        if (empty($event)) {
            $this->session->set_flashdata('error', lang('eventsmanager:exists_error'));
            redirect('admin/eventsmanager');
        }


        // Valid form data?
        if ($this->form_validation->run()) {
            // Try to update the event
            if ($this->eventsmanager_m->update($id, $this->input->post()) == true) {
                $this->session->set_flashdata('success', lang('eventsmanager:update_success'));

                // Redirect back to the form or main page
                $this->input->post('btnAction') == 'save_exit' ? redirect('admin/eventsmanager') : redirect('admin/eventsmanager/manage/' . $id);
            } else {
                $this->session->set_flashdata('error', lang('eventsmanager:update_error'));
                redirect('admin/eventsmanager/manage/' . $id);
            }
        }

        $file_folders = $this->file_folders_m->get_albums($id); // Get the folders 
        $picture      = null;
        $pictures     = array();
        if (isset($event->picture_id))
            $picture      = $this->eventsmanager_m->get_image_file($event->picture_id);
        if (isset($picture))
            $pictures     = $this->eventsmanager_m->get_images_files($picture->folder_id); // Loads pictures from selected picture folder
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

        $_subcategories = array();
        if ($subcategories  = $this->event_categories_m->get_sub_categories($event->category_id)) {
            foreach ($subcategories as $subcategory) {
                $_subcategories[$subcategory->id] = $subcategory->title;
            }
        }
        $this->template
                ->title($this->module_details['name'], lang('eventsmanager:manage_event_label') . ' : "' . $event->title . '"')
                ->append_metadata($this->load->view('fragments/wysiwyg', array(), true))
                ->append_js('module::form.js')
                ->append_js('module::jquery.ui.datepicker-fr.js')
                ->append_js('module::jquery.imgareaselect.pack.js')
                ->append_css('module::admin.css')
                ->append_css('module::coverphoto.css')
                ->append_js('module::coverphoto.js')
                ->append_css('module::imgareaselect/imgareaselect-default.css')
                ->append_metadata("<script type='text/javascript' src='http://maps.google.com/maps/api/js?sensor=false&language=" . $this->current_user->lang . "'></script>")
                ->append_js('crop/jquery.Jcrop.js')
                ->append_css('crop/jquery.Jcrop.css')
                ->set('event', $event)
                ->set('sub_categories', $_subcategories)
                ->set('pictures', $pictures)
                ->set('picture', $picture)
                ->set('folders', $folders_tree)
                ->set('date_format', $this->settings->get('date_format'))
                ->set('date_interval', $this->settings->get('events_date_interval'))
                ->build('admin/form');
    }

    public function delete($id = null)
    {
        $id_array = array();

        // Multiple or single selection ?
        if ($_POST)
            $id_array    = $_POST['action_to'];
        elseif ($id !== null)
            $id_array[0] = $id;

        // If no event was selected
        if (empty($id_array)) {
            $this->session->set_flashdata('error', lang('eventsmanager:empty_selection_error'));
            redirect('admin/eventsmanager');
        } else {
            foreach ($id_array as $id) {
                // Check if the event exists
                if ($this->eventsmanager_m->getBy('id', $id) == null) {
                    $this->session->set_flashdata('error', lang('eventsmanager:exists_error'));
                    redirect('admin/eventsmanager');
                } else {
                    $this->eventsmanager_m->delete($id);
                }
            }
            redirect('admin/eventsmanager');
        }
    }

    /**
     * Preview an event
     * @author Ankit Vishwakarma <ankitvishwakarma@sify.com>
     * @access public
     * @param int $id the ID of the event to preview
     * @return void
     */
    public function preview($id = 0)
    {
        $event = $this->eventsmanager_m->get($id);

        $this->template
                ->set_layout('modal', 'admin')
                ->set('event', $event)
                ->build('admin/preview');
    }

    /**
     * Crop an image and store it into the uploads folder
     *
     * @author Ankit Vishwakarma <ankitvishwakarma@sify.com>
     * @access public
     * @return void
     */
    public function ajax_img_crop()
    {
        $target_w     = $target_h     = 200;
        $jpeg_quality = 100;

        // Getting the parameters
        !empty($_POST['img_src']) ? $src        = $_POST['img_src'] : $src        = "";
        !empty($_POST['disp_w']) ? $src_disp_w = $_POST['disp_w'] : $src_disp_w = 100;
        !empty($_POST['disp_h']) ? $src_disp_h = $_POST['disp_h'] : $src_disp_h = 100;
        $x1         = !empty($_POST['x1']) ? (int) $_POST['x1'] : 0;
        $y1         = !empty($_POST['y1']) ? (int) $_POST['y1'] : 0;
        $x2         = !empty($_POST['x2']) ? (int) $_POST['x2'] : 100;
        $y2         = !empty($_POST['y2']) ? (int) $_POST['y2'] : 100;
        $event_id   = !empty($_POST['event_id']) ? (int) $_POST['event_id'] : 0;

        $image_infos = getimagesize($src);
        $type        = $image_infos['mime'];
        $img_width   = $image_infos[0];
        $img_height  = $image_infos[1];

        // Calculating the ratios
        $ratio_w = (int) $img_width / $src_disp_w;
        $ratio_h = (int) $img_height / $src_disp_h;

        switch ($type) {
            case 'image/jpeg':
                //header('Content-type: image/jpeg');
                $extension = 'jpg';
                $img_r     = imagecreatefromjpeg($src);
                $dst_r     = imagecreatetruecolor($target_w, $target_h);

                $src_width  = $x2 - $x1;
                $src_height = $y2 - $y1;

                imagecopyresampled($dst_r, $img_r, 0, 0, $x1 * $ratio_w, $y1 * $ratio_h, $target_w, $target_h, $src_width * $ratio_w, $src_height * $ratio_h);

                imagejpeg($dst_r, UPLOAD_PATH . 'files/thumbnail_event_' . $event_id . '.jpg', $jpeg_quality);
                imagedestroy($dst_r);
                imagedestroy($img_r);
                break;
            case 'image/png':
                $extension = 'png';
                $img_r     = imagecreatefrompng($src);
                $dst_r     = imagecreatetruecolor($target_w, $target_h);

                $src_width  = $x2 - $x1;
                $src_height = $y2 - $y1;

                imagecopyresampled($dst_r, $img_r, 0, 0, $x1 * $ratio_w, $y1 * $ratio_h, $target_w, $target_h, $src_width * $ratio_w, $src_height * $ratio_h);

                imagepng($dst_r, UPLOAD_PATH . 'files/thumbnail_event_' . $event_id . '.png', 0);
                imagedestroy($dst_r);
                imagedestroy($img_r);
                break;
        }
        echo 'thumbnail_event_' . $event_id . '.' . $extension;
    }

    /**
     * Get images in a folder
     *
     * @author Ankit Vishwakarma <ankitvishwakarma@sify.com>
     * @access public
     * @return JSon
     */
    public function ajax_select_folder($folder_id)
    {
        $folder = $this->file_folders_m->get($folder_id);

        if (isset($folder->id)) {
            $folder->images = $this->eventsmanager_m->get_images_files($folder->id);

            return $this->template->build_json($folder);
        }

        echo FALSE;
    }

    public function ajax_filter()
    {
        //set the layout to false and load the view
        $this->template
                ->set_layout(FALSE)
                ->set('blog', $results)
                ->build('admin/tables/posts');
    }

    public function get_sub_categories()
    {
        $cat_id            = $this->input->post('cat_id', '');
        $parent_categories = $this->event_categories_m->get_sub_categories($cat_id);

        $parents = array();
        foreach ($parent_categories as $pc) {
            $parents[$pc->id] = $pc->title;
        }
        // exit(form_dropdown('sub_category_id', array( '' => lang('cat:no_category_select_label') ) + $parents,'class="drpdwn_sub_category_id"'));
        exit(json_encode($parents));
    }

    public function save_thumbnail()
    {
        // crop it
        $x        = $this->input->post('x');
        $y        = $this->input->post('y');
        $w        = $this->input->post('w');
        $h        = $this->input->post('h');
        $disp_w   = $this->input->post('disp_w');
        $disp_h   = $this->input->post('disp_h');
        $event_id = 1; //$this->input->post('event_id');
        $path     = UPLOAD_PATH . 'files/';
        $name     = 'my_thumbnail_event_' . $event_id;
        $raw      = $this->eventsmanager_m->get_image_file($this->input->post('picture_id'));
        $src      = UPLOAD_PATH . 'files/' . $raw->filename;
        imageCrop($path, $name, $src, $disp_w, $disp_h, $x, $y, $w, $h);

//        $config['image_library'] = 'gd2';
//        $config['source_image'] = $this->input->post('src');
//        $config['create_thumb'] = true;
//        $image_infos = getimagesize($config['source_image']) ;
//        $type        = $image_infos['mime'] ;
//        $extension = '';
//        switch($type){
//            case 'image/jpeg':
//                $extension = 'jpg';
//                break;
//            case 'image/png':
//                $extension = 'png';
//                break;
//        }
//        $config['new_image'] = sprintf($this->config->item('event:thumbnail'), $data['event_id'], time(), $extension);
//        $config['maintain_ratio'] = true;
//        $config['width'] = $data['w'];
//        $config['height'] = $data['h'];
//        $config['x_axis'] = $data['x'];
//        $config['y_axis'] = $data['y'];
//        
//        $this->load->library('image_lib', $config);
//        echo $this->image_lib->crop(); exit; 
//        $msg = array();
//        if(!$this->image_lib->crop()){
//            $msg['msg'] = $this->image_lib->display_errors();
//        }else{
//            $msg['msg'] = sprintf($this->config->item('event:thumbnail'), $data['event_id'], time(), $extension);
//        }
        echo json_encode($msg);
        exit;
    }

    public function change_access_level()
    {
        $file_id      = $this->input->post('file_id');
        $access_level = $this->input->post('access_level');
        $changed      = Files::change_access_level($file_id, $access_level);
        echo $changed;
        exit;
    }
    
    public function approve()
    {
        $unpublished_events = $this->eventsmanager_m->get_many_by(array('published' => 0));
        $this->template
                ->set('unpub_events', $unpublished_events)
                ->build('admin/approve');
    }
    
    public function publish($id = null)
    {
        $id_array = array();

        // Multiple or single selection ?
        if ($_POST)
            $id_array    = $_POST['action_to'];
        elseif ($id !== null)
            $id_array[0] = $id;

        // If no event was selected
        if (empty($id_array)) {
            $this->session->set_flashdata('error', lang('eventsmanager:empty_selection_error'));
            redirect('admin/eventsmanager');
        } else {
            foreach ($id_array as $id) {
                // Check if the event exists
                if ($this->eventsmanager_m->getBy('id', $id) == null) {
                    $this->session->set_flashdata('error', lang('eventsmanager:exists_error'));
                    redirect('admin/eventsmanager');
                } else {
                    $this->eventsmanager_m->publish_event($id, array('published' => 1, 'published_at' => date('Y-m-d H:i:s')));
                }
            }
            redirect('admin/eventsmanager');
        }
    }

}
