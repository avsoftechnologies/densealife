<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Categories model
 *
 * @author  Ankit Vishwakarma <ankitvishwakarma@sify.com>
 * @package Addons\Default\Modules\Eventsmanger\Models
 */
class Event_Followers_m extends MY_Model
{

    protected $_table = 'event_followers';

    const EVENT_FOLLOWERS_FOLLOW    = '1';
    CONST EVENT_FOLLOWERS_FAVOURITE = '2';
    CONST EVENT_FOLLOWERS_STARED    = '3';

    public function insert($input, $skip_validation = false)
    {
        if (!$this->check_already_followed($input)) {
            return parent::insert(array(
                        'event_slug' => $input['slug'],
                        'follow'     => $input['follow'],
                        'user_id'    => $this->current_user->id,
                    ));
        }
    }

    public function count_followers($slug, $follow_type)
    {
        return $this->count_by(array(
                    'event_slug' => $slug,
                    'follow'     => $follow_type,
        ));
    }

    /**
     * Update an existing category
     *
     * @param int   $id    The ID of the category
     * @param array $input The data to update
     * @param bool  $skip_validation
     *
     * @return bool
     */
    public function update($id, $input, $skip_validation = false)
    {
        return parent::update($id, array(
                    'title'       => $input['title'],
                    'slug'        => $input['slug'],
                    'description' => $input['description'],
                    'author'      => $input['author'],
                    'parent_id'   => !empty($input['parent_id']) ? $input['parent_id'] : 0
                ));
    }

    /**
     * Callback method for validating the title
     *
     * @param string $title The title to validate
     * @param int    $id    The id to check
     *
     * @return mixed
     */
    public function check_already_followed($input)
    {
        return (bool) $this->db->where(array(
                            'event_slug' => $input['slug'],
                            'follow'     => $input['follow'],
                            'user_id'    => $this->current_user->id))
                        ->from($this->_table)
                        ->count_all_results();
    }

    public function am_i_following($slug)
    {
        return (bool) $this->db->where(array(
                            'event_slug' => $slug,
                            'follow'     => 'follow',
                            'user_id'    => $this->current_user->id))
                        ->from($this->_table)
                        ->count_all_results();
    }
    

}
