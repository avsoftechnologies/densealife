<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * The User model.
 *
 * @author PyroCMS Dev Team
 * @package PyroCMS\Core\Modules\Users\Models
 */
class Album_m extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('files/Files');
    }

    public function get_albums($user_id = null, $event_id = null)
    {
        return Files::folder_contents(0, $event_id, true, $user_id);
    }

    public function get_image_files($user_id = null, $folder_id = null)
    {
        $images = $this->db
                ->select('files.*')
                ->where('files.type', 'i'); // Only the images files
        if (isset($folder_id)) {
            $this->db->where('folder_id', $folder_id);
        }
        if ($user_id != '') {
            $this->db->where('user_id', $user_id);
        }
        $this->db->order_by('sort', 'desc');
        return $images->get('files')->result();
    }

    public function remove_album($album_id)
    {
        $this->db->update('file_folders', array('hidden' => 1), array('id' => $album_id));
        $this->db->update('files', array('hidden' => 1), array('folder_id' => $album_id));
    }
    
    public function remove_photo($photo_id) {
        $this->db->update('files', array('hidden' => 1), array('id' => $photo_id));
    }
}
