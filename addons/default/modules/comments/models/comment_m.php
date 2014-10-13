<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Comment model
 * 
 * @author		PyroCMS Dev Team
 * @package		PyroCMS\Core\Modules\Comments\Models
 */
class Comment_m extends MY_Model
{

    /**
     * Get a comment based on the ID
     * 
     * @param int $id The ID of the comment
     * @return array
     */
    public function get($id)
    {
        return $this->db->select('c.*')
                        ->select('IF(c.user_id > 0, m.display_name, c.user_name) as user_name', false)
                        ->select('IF(c.user_id > 0, u.email, c.user_email) as user_email', false)
                        ->select('u.username')
                        ->from('comments c')
                        ->join('users u', 'c.user_id = u.id', 'left')
                        ->join('profiles m', 'm.user_id = u.id', 'left')

                        // If there is a comment user id, make sure the user still exists
                        ->where('IF(c.user_id > 0, c.user_id = u.id, 1)')
                        ->where('c.id', $id)
                        ->get()
                        ->row();
    }

    /**
     * Get recent comments
     *
     * 
     * @param int $limit The amount of comments to get
     * @param int $is_active set default to only return active comments
     * @return array
     */
    public function get_recent($limit = 10, $is_active = 1)
    {
        $this->_get_all_setup();

        $this->db
                ->where('c.is_active', $is_active)
                ->order_by('c.created_on', 'desc');

        if ($limit > 0) {
            $this->db->limit($limit);
        }

        return $this->get_all();
    }

    /**
     * Get something based on a module item
     *
     * @param string $module The name of the module
     * @param int $entry_key The singular key of the entry (E.g: blog:post or pages:page)
     * @param int $entry_id The ID of the entry
     * @param bool $is_active Is the comment active?
     * @return array
     */
    public function get_by_entry($module, $entry_key, $entry_id, $is_active = true, $parent_id = 0, $comment_id = 0)
    {
        echo $this->current_user->id;
        $this->get_comment_by_event($entry_id); 
        exit; 
        $this->_get_all_setup();
        $this->db->select('IF(e.author = c.user_id, e.title, (IF(c.user_id > 0, m.display_name, c.user_name))) as display_name',false);
        $this->db->join('events as e', 'c.entry_id = e.id','left');
        $this->db
                ->select('c.created_on as priority')
                ->where('c.module', $module)
                ->where('c.entry_id', $entry_id)
                ->where('c.entry_key', $entry_key)
                ->where('c.is_active', $is_active)
                ->where('c.parent_id', $parent_id);
        if ($comment_id != 0) {
            $this->db->where('c.id', $comment_id);
        }
        if ($parent_id == 0) {
            $this->db->order_by('c.created_on', Settings::get('comment_order'));
        }

        return $this->get_all();
    }

    public function get_by_user($user_id, $parent_id = 0, $is_main_post = true, $limit = null, $offset = null)
    {
        $this->load->model(array('friend/friend_m', 'trend/trend_m'));
        
        $friends = $this->friend_m->get_friends($user_id, 'p.user_id');
        
        $following_entry_ids = array(); 
        if($parent_id == 0) {
            $followings= $this->trend_m->get_followings($user_id);
            foreach($followings as $following) {
                $following_entry_ids[] = $following->entry_id;
            }
        }
        $friends_ids = array();
        foreach($friends as $friend) {
            $friends_ids[] = $friend->user_id;
        }
        $this->_get_all_setup();
        $sharedIncluded = false;
        $this->db->select('s.comment as comment_on_share, s.shared_at, p1.display_name as shared_by, e.author');
        $this->db->select("IF(s.shared_at is null, c.created_on,s.shared_at) AS priority", false);
        $this->db->join('shares as s','s.fk_comment_id = c.id', 'left');
        $this->db->join('profiles as p1', 'p1.user_id = s.user_id', 'left');
        $this->db->join('events as e', 'e.id = c.entry_id', 'left');
        $where = "(c.user_id = $user_id)";
        //Bug#167:resolved
        if(!empty($friends_ids)){
            $where = "(s.user_id IN (". implode(',',$friends_ids).") OR c.user_id IN (". implode(',',$friends_ids).") OR s.user_id =".$user_id." OR c.user_id =".$user_id.") ";
            $sharedIncluded = true; 
        }
        
        if(!empty($following_entry_ids) and $parent_id ==0) {
            $where.="AND (c.entry_id IN(".implode(',', $following_entry_ids).") )";
        }
        
        $this->db
                ->where($where)
                ->where('c.parent_id', $parent_id)
                ->where('c.is_active', 1);
        
        if($this->router->fetch_module() == 'users') {
         $this->db->having('author != `user_id`');
         $this->db->where("e.author='".$this->current_user->id."' or c.user_id ='".$this->current_user->id."'");
        }
        if ($parent_id == 0) {
            $this->db->order_by('priority','DESC');
        }
        
        if(!$is_main_post){
            $limit = !is_null($limit)  ? $limit : Comments::LIMIT_POST_COMMENTS;
            $offset = !is_null($offset) ? $offset : 0 ; 
            $this->db->limit($limit, $offset);
        }
        $result_set = $this->get_all();
        //echo $this->last_query();
        return $result_set;
    }

    public function get_latest_entry($module, $entry_key, $entry_id, $is_active = true, $parent_id = 0)
    {
        $this->_get_all_setup();

        $this->db
                ->where('c.module', $module)
                ->where('c.entry_id', $entry_id)
                ->where('c.entry_key', $entry_key)
                ->where('c.is_active', $is_active)
                ->where('c.parent_id', $parent_id);
        if ($parent_id == 0) {
            $this->db->order_by('c.created_on', Settings::get('comment_order'));
        }

        return $this->get_all();
    }

    /**
     * Insert a new comment
     *
     * @param array $input The data to insert
     * @return bool
     */
    public function insert($input, $skip_validation = false)
    {

        if ($input['entry_id'] == '' && !empty($input['parent_id'])) {
            $this->load->model('eventsmanager/eventsmanager_m');
            $event                 = $this->get_by(array('id' => $input['parent_id']));
            $input['module']       = $event->module;
            $input['entry_id']     = $event->entry_id;
            $input['entry_title']  = $event->entry_title;
            $input['entry_key']    = $event->entry_key;
            $input['entry_plural'] = $event->entry_plural;
        }
        return parent::insert(array(
                    'parent_id'    => !empty($input['parent_id']) ? $input['parent_id'] : 0,
                    'user_id'      => isset($input['user_id']) ? $input['user_id'] : 0,
                    'user_name'    => isset($input['user_name']) && !isset($input['user_id']) ? ucwords(strtolower(strip_tags($input['user_name']))) : '',
                    'user_email'   => isset($input['user_email']) && !isset($input['user_id']) ? strtolower($input['user_email']) : '',
                    'user_website' => isset($input['user_website']) ? prep_url(strip_tags($input['user_website'])) : '',
                    'is_active'    => !empty($input['is_active']),
                    'comment'      => htmlspecialchars($input['comment'], null, false),
                    'parsed'       => parse_markdown(htmlspecialchars($input['comment'], null, false)),
                    'module'       => $input['module'],
                    'entry_id'     => $input['entry_id'],
                    'entry_title'  => $input['entry_title'],
                    'entry_key'    => $input['entry_key'],
                    'entry_plural' => $input['entry_plural'],
                    'uri'          => !empty($input['uri']) ? $input['uri'] : null,
                    'cp_uri'       => !empty($input['cp_uri']) ? $input['cp_uri'] : null,
//                    'created_on'   => now(),
                    'ip_address'   => $this->input->ip_address(),
                    'media'        => !empty($input['media']) ? $input['media'] : null,
                ));
    }

    /**
     * Update an existing comment
     *
     * @param int $id The ID of the comment to update
     * @param array $input The array containing the data to update
     * @return void
     */
    public function update($id, $input, $skip_validation = false)
    {
        return parent::update($id, array(
                    'user_name'    => isset($input['user_name']) ? ucwords(strtolower(strip_tags($input['user_name']))) : '',
                    'user_email'   => isset($input['user_email']) ? strtolower($input['user_email']) : '',
                    'user_website' => isset($input['user_website']) ? prep_url(strip_tags($input['user_website'])) : '',
                    'comment'      => htmlspecialchars($input['comment'], null, false),
                    'parsed'       => parse_markdown(htmlspecialchars($input['comment'], null, false)),
        ));
    }

    /**
     * Approve a comment
     *
     * @param int $id The ID of the comment to approve
     * @return mixed
     */
    public function approve($id)
    {
        return parent::update($id, array('is_active' => true));
    }

    /**
     * Unapprove a comment
     *
     * @param int $id The ID of the comment to unapprove
     * @return mixed
     */
    public function unapprove($id)
    {
        return parent::update($id, array('is_active' => false));
    }

    public function get_slugs()
    {
        $this->db
                ->select('comments.module, modules.name')
                ->distinct()
                ->join('modules', 'comments.module = modules.slug', 'left');

        $slugs = parent::get_all();

        $options = array();

        if (!empty($slugs)) {
            foreach ($slugs as $slug) {
                if (!$slug->name and ( $pos = strpos($slug->module, '-')) !== false) {
                    $slug->ori_module = $slug->module;
                    $slug->module     = substr($slug->module, 0, $pos);
                }

                if (!$slug->name and $module = $this->module_m->get_by('slug', plural($slug->module))) {
                    $slug->name = $module->name;
                }

                //get the module name
                if ($slug->name and $module_names = unserialize($slug->name)) {
                    if (array_key_exists(CURRENT_LANGUAGE, $module_names)) {
                        $slug->name = $module_names[CURRENT_LANGUAGE];
                    } else {
                        $slug->name = $module_names['en'];
                    }

                    if (isset($slug->ori_module)) {
                        $options[$slug->ori_module] = $slug->name . " ($slug->ori_module)";
                    } else {
                        $options[$slug->module] = $slug->name;
                    }
                } else {
                    if (isset($slug->ori_module)) {
                        $options[$slug->ori_module] = $slug->ori_module;
                    } else {
                        $options[$slug->module] = $slug->module;
                    }
                }
            }
        }

        asort($options);

        return $options;
    }

    /**
     * Get something based on a module item
     *
     * @param string $module The name of the module
     * @param int $entry_key The singular key of the entry (E.g: blog:post or pages:page)
     * @param int $entry_id The ID of the entry
     * @return bool
     */
    public function delete_by_entry($module, $entry_key, $entry_id)
    {
        return $this->db
                        ->where('module', $module)
                        ->where('entry_id', $entry_id)
                        ->where('entry_key', $entry_key)
                        ->delete('comments');
    }

    /**
     * Setting up the query for the get* functions
     */
    private function _get_all_setup()
    {
        $this->_table = null;
        $this->db
                ->select('c.*')
                ->from('comments c')
                ->select('IF(c.user_id > 0, m.display_name, c.user_name) as user_name', false)
                ->select('IF(c.user_id > 0, u.email, c.user_email) as user_email', false)
                ->select('u.username, m.display_name')
                ->join('users u', 'c.user_id = u.id', 'left')
                ->join('profiles m', 'm.user_id = u.id', 'left');
    }
    
    public function get_wall_posts($user_id)
    {
        if(!is_null($user_id)){
            $var1    = "";
            $var1 .= "SELECT `p`.user_id ";
            $var1 .= "FROM   `default_profiles` AS `p` ";
            $var1 .= "       INNER JOIN `default_users` AS `u` ";
            $var1 .= "               ON `p`.`user_id` = `u`.`id` ";
            $var1 .= "WHERE  `u`.`id` IN (SELECT friend_id ";
            $var1 .= "                    FROM   default_friend_list ";
            $var1 .= "                    WHERE  user_id = $user_id ";
            $var1 .= "                           AND status = 'accepted' ";
            $var1 .= "                    UNION ";
            $var1 .= "                    SELECT user_id ";
            $var1 .= "                    FROM   default_friend_list ";
            $var1 .= "                    WHERE  friend_id = $user_id ";
            $var1 .= "                           AND status = 'accepted') ";
        $this->_get_all_setup();
        $this->db
                ->join('default_shares as ds','c.id = fk_comment_id', 'left')
                ->where('c.user_id', $user_id)
                ->where('c.parent_id', 0)
                ->or_where('ds.user_id IN ('.$var1.')');

        $this->db->order_by('c.created_on', Settings::get('comment_order'));
        return $this->get_all();
        }
    }
    
    public function soft_delete($post_id)
    {
        return parent::update($post_id, array('is_active' => 0)); 
    }
    
    public function get_by_parent($parent_id, $limit, $offset)
    {
        $this->_get_all_setup();

        $this->db
                ->where('c.parent_id', $parent_id)
                ->where('c.is_active', 1);
        $limit = !is_null($limit)  ? $limit : Comments::LIMIT_POST_COMMENTS;
        $offset = !is_null($offset) ? $offset : 0 ; 
        $this->db->limit($limit, $offset);
        
        $rs = $this->get_all();
        return $rs; 
    }
    
    public function get_comment_by_event($entry_id, $parent_comment_id = 0,  $show_inactive = false)
    {
        $user_id = $this->current_user->id; 
        $friend_ids = "SELECT "
                . "CASE "
                . "  WHEN "
                . "      user_id = '" . $user_id . "' THEN friend_id "
                . "  WHEN "
                . "      friend_id = '" . $user_id . "' THEN user_id "
                . "END AS fid"
                . " FROM default_friend_list"
                . " WHERE status='accepted' "
                . " AND (user_id='" . $user_id . "' OR friend_id='" . $user_id . "')";
        
        $this
                ->select('comments.*')
                ->select('e.author as event_author_id')
                ->select("if(default_comments.user_id IN ($friend_ids), 1, 0) as is_friend_post", false)
                ->select('if(e.author = default_comments.user_id, e.title, p.display_name) as display_name', false )
                ->select('default_comments.created_on as priority')
                ->join('events as e' , 'e.id = comments.entry_id', 'left')
                ->join('profiles as p', 'p.user_id = comments.user_id', 'inner');
         if ($parent_comment_id == 0) {
            $this->order_by('comments.created_on', Settings::get('comment_order'));
        }
        $comments = $this->get_many_by(array('entry_id' => $entry_id, 'is_active' => 1, 'default_comments.parent_id' => 0));
        
        return $comments;
    }
    
    public function get_comment_details($comment_id)
    {
        return $this
                ->select('e.author as event_author_id, c.user_id as post_author_id', true)
                ->from('comments as c')
                ->join('events as e', 'e.id = c.entry_id', 'left')
                ->get_by(array('c.id' => $comment_id)); 
    }
}
