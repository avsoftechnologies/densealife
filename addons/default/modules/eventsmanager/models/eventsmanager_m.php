<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 *
 *
 * @author      Ankit Vishwakarma <ankitvishwakarma@sify.com>
 * @package 	PyroCMS
 * @subpackage 	Events Manager Module
 * @category 	Modules
 * @license 	Copyright
 */
class EventsManager_m extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->_table = 'events';
        $this->load->model('eventsmanager/event_categories_m');
    }

    public function get_all_events($user_id = null, $entry_type = 'event', $limit = null)
    {
        $this->db->select()
                ->from($this->_table . ' as e');
        if (!empty($user_id)) {
            //->join('trends as t', 'e.id = t.entry_id', 'right')
            $this->db->where('e.author', $user_id);
        }
        if ($entry_type == 'event') {
            $this->db->where('category_id', '1');
        } else {
            $this->db->where('category_id', '2');
        }

        //->where('t.entry_type', $entry_type);
//        if ( $user_id != '' ) {
//            $this->db->where('t.user_id', $user_id);
//        }

        if ($limit != '') {
            $this->db->limit($limit);
        }
        $rs = $this->db->order_by('id', 'DESC')->get()
                ->result();
//        $rs = $this->db->order_by('star_count', 'DESC')
//                ->order_by('follow_count', 'DESC')
//                ->order_by('favorite_count', 'DESC')
//                ->get()
//                ->result();

        return $rs;
    }

    /**
     * Get all events, within the specified conditions
     *
     * @author Ankit Vishwakarma <ankitvishwakarma@sify.com>
     * @access public
     * @return mixed
     */
    public function get_all($conds = array(), $sort_direction = 'asc')
    {
        $this->db->select()->order_by('start_date', $sort_direction);
        if (!empty($conds))
            foreach ($conds as $cond)
                $this->db->where($cond);

        $events = parent::get_all();
        if (!empty($events)) {
            // Format the dates to the site format
            foreach ($events as $event) {
                $event->start_time = date('H:i', strtotime($event->start_date));
                $event->end_time   = date('H:i', strtotime($event->end_date));
            }
            return $events;
        } else
            return array();
    }

    /**
     * Get an event thanks to a parameter
     *
     * @author Ankit Vishwakarma <ankitvishwakarma@sify.com>
     * @access public
     * @return mixed
     */
    public function getBy($param, $value)
    {
        $query = $this->db->query("SELECT * FROM " . $this->db->dbprefix('events') . " WHERE `" . $param . "` = '" . $value . "'");
        if ($query->num_rows() == 0)
            return null;
        else {
            $result            = $query->result();
            $event             = $result[0];
            $event->start_time = date('H:i', strtotime($event->start_date));
            $event->end_time   = date('H:i', strtotime($event->end_date));
            return $event;
        }
    }

    /**
     * Remove an event thanks to its id
     *
     * @author Ankit Vishwakarma <ankitvishwakarma@sify.com>
     * @access public
     * @return mixed
     */
    public function delete($id)
    {
        $query = $this->db->query("DELETE FROM " . $this->db->dbprefix('events') . " WHERE id = " . $id);
        return $query;
    }

    private function _process($input)
    {
        if (!empty($input['start_date'])) {
            // Format the date for the database
            $DATE_FORMAT    = $this->settings->get('date_format');
            $start_datetime = date_create_from_format($DATE_FORMAT, $input['start_date']);
            $start_datetime->setTime($input['start_time_hour'], $input['start_time_minute']);
            if ($input['end_date_defined']) {
                $end_datetime = date_create_from_format($DATE_FORMAT, $input['end_date']);
                $end_datetime->setTime($input['end_time_hour'], $input['end_time_minute']);
            } else {
                $end_datetime = clone $start_datetime;
                $end_datetime->modify('+1 hour');
            }
        }
        $insert_row = array(
            'category_id'        => $input['category_id'],
            'sub_category_id'    => $input['sub_category_id'],
            'title'              => $input['title'],
            'slug'               => $input['slug'],
            'about'              => $input['about'],
            'website'            => isset($input['website']) ? $input['website'] : null,
            'affiliations'       => isset($input['affiliations']) ? $input['affiliations'] : null,
            'description'        => $input['description'],
            'place'              => $input['place'],
            'author'             => $input['author'],
            'start_date'         => (!empty($start_datetime) && is_object($start_datetime)) ? $start_datetime->format('Y-m-d H:i:s') : '0000-00-00 00:00:00',
            'end_date'           => (!empty($end_datetime) && is_object($end_datetime)) ? $end_datetime->format('Y-m-d H:i:s') : '0000-00-00 00:00:00',
            'end_date_defined'   => $input['end_date_defined'],
            'enable_comments'    => $input['enable_comments'],
            'published'          => isset($input['published']) ? $input['published'] : 1,
            'youtube_videos'     => isset($input['youtube_videos']) ? serialize($input['youtube_videos']) : null,
            'comment_permission' => isset($input['comment_permission']) ? $input['comment_permission'] : 'CREATER',
            'comment_approval'   => isset($input['comment_approval']) ? $input['comment_approval'] : 'NO'
        );

        if (isset($input['cover_photo'])) {
            $insert_row['cover_photo'] = $input['cover_photo'];
        }
        if ($this->current_user->group == 'admin') {
            $insert_row['published'] = 1;
        } else {
            $insert_row['published'] = 0;
        }

        return $insert_row;
    }

    /**
     * Add an event
     *
     * @author Ankit Vishwakarma <ankitvishwakarma@sify.com>
     * @access public
     * @return mixed
     */
    public function insert($input, $skip_validation = false)
    {
        $array = $this->_process($input);
        $id    = (int) parent::insert($array);

        // Doing this work after for PHP < 5.3
        if (isset($input['picture_id'])) {
            parent::update($id, array('picture_id' => $input['picture_id']));

            // Generate the thumbnail
            $x1 = $input['thumbnail_x1'];
            $y1 = $input['thumbnail_y1'];
            $x2 = $input['thumbnail_x2'];
            $y2 = $input['thumbnail_y2'];
            if (!empty($x1) && !empty($y1) && !empty($x2) && !empty($y2)) { // If there is a new selection
                $path   = UPLOAD_PATH . 'files/';
                $name   = 'thumbnail_event_' . $id;
                $raw    = $this->get_image_file($input['picture_id']);
                $src    = UPLOAD_PATH . 'files/' . $raw->filename;
                $disp_w = str_replace('px', '', $input['thumbnail_disp_w']);
                $disp_h = str_replace('px', '', $input['thumbnail_disp_h']);
                imageCrop($path, $name, $src, $disp_w, $disp_h, $x1, $y1, $x2, $y2);
                parent::update($id, array('thumbnail' => $name . $raw->extension));
            }
        }

        // Maps
        if (isset($input['show_map']))
            parent::update($id, array('show_map' => $input['show_map']));
        else
            parent::update($id, array('show_map' => false));
        if (isset($input['pos_method'])) {
            if ($input['pos_method'] == 0) // Automatic mode
                parent::update($id, array('pos_lat' => null, 'pos_lng' => null));
            else // Latitude/longitude mode
                parent::update($id, array('pos_lat' => $input['pos_lat'], 'pos_lng' => $input['pos_lng']));
        }
        return $id;
    }

    //You do not need to alter these functions
    function getHeight($image)
    {
        $size   = getimagesize($image);
        $height = $size[1];
        return $height;
    }

//You do not need to alter these functions
    function getWidth($image)
    {
        $size  = getimagesize($image);
        $width = $size[0];
        return $width;
    }

    ##########################################################################################################
# IMAGE FUNCTIONS																						 #
# You do not need to alter these functions																 #
##########################################################################################################

    function resizeImage($image, $width, $height, $scale)
    {
        list($imagewidth, $imageheight, $imageType) = getimagesize($image);
        $imageType      = image_type_to_mime_type($imageType);
        $newImageWidth  = ceil($width * $scale);
        $newImageHeight = ceil($height * $scale);
        $newImage       = imagecreatetruecolor($newImageWidth, $newImageHeight);
        switch ($imageType) {
            case "image/gif":
                $source = imagecreatefromgif($image);
                break;
            case "image/pjpeg":
            case "image/jpeg":
            case "image/jpg":
                $source = imagecreatefromjpeg($image);
                break;
            case "image/png":
            case "image/x-png":
                $source = imagecreatefrompng($image);
                break;
        }
        imagecopyresampled($newImage, $source, 0, 0, 0, 0, $newImageWidth, $newImageHeight, $width, $height);

        switch ($imageType) {
            case "image/gif":
                imagegif($newImage, $image);
                break;
            case "image/pjpeg":
            case "image/jpeg":
            case "image/jpg":
                imagejpeg($newImage, $image, 90);
                break;
            case "image/png":
            case "image/x-png":
                imagepng($newImage, $image);
                break;
        }

        chmod($image, 0777);
        return $image;
    }

//You do not need to alter these functions
    function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale)
    {
        list($imagewidth, $imageheight, $imageType) = getimagesize($image);
        $imageType = image_type_to_mime_type($imageType);

        $newImageWidth  = ceil($width * $scale);
        $newImageHeight = ceil($height * $scale);
        $newImage       = imagecreatetruecolor($newImageWidth, $newImageHeight);
        switch ($imageType) {
            case "image/gif":
                $source = imagecreatefromgif($image);
                break;
            case "image/pjpeg":
            case "image/jpeg":
            case "image/jpg":
                $source = imagecreatefromjpeg($image);
                break;
            case "image/png":
            case "image/x-png":
                $source = imagecreatefrompng($image);
                break;
        }
        imagecopyresampled($newImage, $source, 0, 0, $start_width, $start_height, $newImageWidth, $newImageHeight, $width, $height);
        switch ($imageType) {
            case "image/gif":
                imagegif($newImage, $thumb_image_name);
                break;
            case "image/pjpeg":
            case "image/jpeg":
            case "image/jpg":
                imagejpeg($newImage, $thumb_image_name, 90);
                break;
            case "image/png":
            case "image/x-png":
                imagepng($newImage, $thumb_image_name);
                break;
        }
        chmod($thumb_image_name, 0777);
        return $thumb_image_name;
    }

    public function save_thumbnail($id, $input)
    {

        $max_width    = "500";       // Max width allowed for the large image
        $thumb_width  = "100";      // Width of thumbnail image
        $thumb_height = "100";
        $raw          = $this->get_image_file($input['picture_id']);

        $large_image_location = UPLOAD_PATH . 'files/' . $raw->filename;
        $width                = $this->getWidth($large_image_location);
        $height               = $this->getHeight($large_image_location);
        //Scale the image if it is greater than the width set above
        if ($width > $max_width) {
            $scale    = $max_width / $width;
            $uploaded = $this->resizeImage($large_image_location, $width, $height, $scale);
        } else {
            $scale    = 1;
            $uploaded = $this->resizeImage($large_image_location, $width, $height, $scale);
        }

        //Delete the thumbnail file so the user can create a new one
        if (file_exists($thumb_image_location)) {
            unlink($thumb_image_location);
        }
        if (isset($input['picture_id'])) {
            parent::update($id, array('picture_id' => $input['picture_id']));

            // Generate the thumbnail
            $x1 = $input['thumbnail_x1'];
            $y1 = $input['thumbnail_y1'];
            $x2 = $input['thumbnail_x2'];
            $y2 = $input['thumbnail_y2'];
            if (!empty($x1) && !empty($y1) && !empty($x2) && !empty($y2)) { // If there is a new selection
                $path = UPLOAD_PATH . 'files/';
                $name = 'thumbnail_event_' . $id;
                $raw  = $this->get_image_file($input['picture_id']);

                $src    = UPLOAD_PATH . 'files/' . $raw->filename;
                $disp_w = str_replace('px', '', '500');
                $disp_h = str_replace('px', '', '500');
                imageCrop($path, $name, $src, $disp_w, $disp_h, $x1, $y1, $x2, $y2);
                return parent::update($id, array('thumbnail' => $name . $raw->extension));
            }
        }
    }

    public function publish_event($id, $input)
    {
        return parent::update($id, $input);
    }

    public function update($id, $input, $skip_validation = false)
    {
        $array  = $this->_process($input);
        // Update all except author
        $result = parent::update($id, $array);
        
        // Doing this work after for PHP < 5.3
        //$result = $this->save_thumbnail($id, $input);
        // Maps
        if (isset($input['show_map']) or isset($input['show_map_clone'])) {
            $show_map = !empty($input['show_map']) ? $input['show_map'] : (!empty($input['show_map_clone']) ? $input['show_map_clone'] : false);
            $result   = parent::update($id, array('show_map' => $show_map));
        } else {            
            $result = parent::update($id, array('show_map' => false));
        }         
        if (isset($input['pos_method'])) {
            if ($input['pos_method'] === 0) { // Automatic mode
                $result = parent::update($id, array('pos_lat' => NULL, 'pos_lng' => NULL));
            } else { // Latitude/longitude mode
                $result = parent::update($id, array('pos_lat' => $input['pos_lat'], 'pos_lng' => $input['pos_lng']));
            }
        }
        return $result;
    }

    public function get_files($type = 'i', $folder_id = null)
    {
        $images = $this->db
                ->select('files.*')
                ->where('files.type', $type); // Only the images files
        if (isset($folder_id)) {
            $this->db->where('folder_id', $folder_id);
        }
        return $images->get('files')->result();
    }

    public function get_images_files($folder_id = null)
    {
        $images = $this->db
                ->select('files.*')
                ->where('files.type', 'i'); // Only the images files
        if (isset($folder_id)) {
            $this->db->where('folder_id', $folder_id);
        }
        $this->db->where('hidden', 0);
        return $images->get('files')->result();
    }

    public function get_user_uploads_by_event_id($event_id, $type = 'i')
    {
        $images = $this->db
                ->select('files.*')
                ->from('files')
                ->join('file_folders as ff', 'ff.id = files.folder_id', 'inner')
                ->where('files.type', $type)  // Only the images files
                ->where('ff.event_id', $event_id)
                ->where('ff.by_user', 'true');
        return $images->get()->result();
    }

    public function get_image_file($image_id, $folder_id = null)
    {
        $image = $this->db
                ->select('files.*')
                ->where('files.type', 'i') // Only the images files
                ->where('files.id', $image_id); // Only the images files
        if (isset($folder_id)) {
            $this->db->where('folder_id', $folder_id);
        }
        $result = $image->get('files')->result();
        if (!empty($result)) {
            return $result[0];
        }
        return null;
    }

    public function get_next($limit, $published = true)
    {
        $events = $this->db
                ->select()
                ->where('(start_date >= NOW()')
                ->or_where('end_date >= NOW())')
                ->order_by('start_date', 'asc')
                ->limit($limit);
        if ($published) {
            $this->db->where('published', 1);
        }
        return $events->get('events')->result();
    }

    public function get_upcoming($user_id = null, $type = 'event', $sub_cat_id = null, $limit = null)
    {
        if ($type == 'interest') {
            $cat         = $this->event_categories_m->get_by('slug', 'interest');
            $category_id = $cat->id;
        } else {
            $cat         = $this->event_categories_m->get_by('slug', 'event');
            $category_id = $cat->id;
        }
        $condition = array('category_id' => $category_id, 'start_date > ' => date('Y-m-d'), 'published' => 1);

        if ($sub_cat_id != '') {
            $condition = $condition + array('sub_category_id' => $sub_cat_id);
        }
        if ($limit != '') {
            $this->limit($limit);
        }

        $this->order_by('star_count', 'DESC');
        $this->order_by('follow_count', 'DESC');
        $this->order_by('favorite_count', 'DESC');
        $upcoming = $this->get_many_by($condition);
        return $upcoming;
    }

    public function get_star_count($entry_id)
    {
        $rs = $this->select('star_count')
                ->get_by(array('id' => $entry_id));
        return $rs->star_count;
    }

    public function add_star($entry_id)
    {
        $this->update($entry_id, array('star_count' => 'star_count' + 1));
    }

    /**
     * Get the detail of all the friends who are following the event
     * @param int $event_id 
     */
    public function get_follower_friends($event_id)
    {
        $this->load->library('friend/friend');
        return $this->friend->get_follower_friends($event_id, $this->current_user->id);
    }

    public function save_image_as($id, $input)
    {
        $raw = $this->get_image_file($input['picture_id']);
        if (isset($input['picture_id'])) {
            if ($input['type'] == 'thumb') {
                return parent::update($id, array('picture_id' => $input['picture_id'], 'thumbnail' => $raw->filename));
            } else {
                return parent::update($id, array('picture_id' => $input['picture_id'], 'cover_photo' => $raw->filename));
            }
        }
    }

    public function save_cp_pos($id, $input)
    {
        return parent::update($id, array('cover_photo_pos' => str_replace('px', '', $input['new_cp_pos'])));
    }

    /**
     * To fetch all the entry under category event or slug
     * @param int $user_id
     * @param string $entry_type event|slug
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function get_following_entry($user_id, $entry_type = 'event', $limit = 10, $offset = 0)
    {
        $cat_id              = $this->get_entry_category_id_by_slug($entry_type);
        $following_interests = $this->join('trends as t', 't.entry_id = events.id', 'inner')
                ->limit($limit, $offset)
                ->get_many_by(
                    array(
                        't.user_id'          => $user_id,
                        't.follow'           => 'true',
                        'events.category_id' => $cat_id,
                        't.entry_type'       => $entry_type
                    )
                );
        return $following_interests;
    }
    
    /**
     * To fetch the count of entries any particular user is following
     * @param int $user_id
     * @param string $entry_type
     * @return int 
     */
    public function get_following_entry_count($user_id, $entry_type = 'event') 
    {
        $cat_id = $this->get_entry_category_id_by_slug($entry_type); 
        $count = $this->join('trends as t', 't.entry_id = events.id', 'inner')
                    ->count_by(
                            array(
                                't.user_id' => $user_id, 
                                't.follow' => 'true',
                                'events.category_id' => $cat_id,
                                't.entry_type'       => $entry_type
                            )
                    );
        return $count; 
        
    }
    
    /**
     * To fetch the category id by slug under any event or interest. 
     * @param string $slug
     * @return int
     */
    public function get_entry_category_id_by_slug($slug)
    {
        $cat = $this->event_categories_m->get_by('slug', $slug);
        return $cat->id;
    }
    
    public function get_favorite_entry($user_id, $limit = 10, $offset = 0)
    {
        $favorite_entry = $this->join('trends as t', 't.entry_id = events.id', 'left')
                ->limit($limit, $offset)
                ->get_many_by(
                        array(
                                't.user_id' => $user_id,
                                't.follow' => 'true',
                        )
                );
        return $favorite_entry;
    }
    
    public function get_favorite_entry_count($user_id)
    {
        $count = $this->join('trends as t', 't.entry_id = events.id', 'left')
                ->count_by(
                        array(
                                't.user_id' => $user_id,
                                't.follow' => 'true',
                        )
                );
        return $count;
    }
}
