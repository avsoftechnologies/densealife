<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Trend model
 * 
 * @author		PyroCMS Dev Team
 * @package		PyroCMS\Core\Modules\Trends\Models
 */
class Trend_m extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('eventsmanager/eventsmanager_m');
        $this->load->model('eventsmanager/event_categories_m');
    }

    public function add_star($entry_id)
    {
        $this->query("UPDATE default_events SET star_count = star_count+1 WHERE id=$entry_id");
    }

    public function remove_star($entry_id)
    {
        $this->query("UPDATE default_events SET star_count = star_count-1 WHERE id=$entry_id");
    }

    public function add_follow($entry_id)
    {
        $this->query("UPDATE default_events SET follow_count = follow_count+1 WHERE id=$entry_id");
    }

    public function remove_follow($entry_id)
    {
        $this->query("UPDATE default_events SET follow_count = follow_count-1 WHERE id=$entry_id");
    }

    public function add_favorite($entry_id)
    {
        $this->query("UPDATE default_events SET favorite_count = favorite_count+1 WHERE id=$entry_id");
    }

    public function remove_favorite($entry_id)
    {
        $this->query("UPDATE default_events SET favorite_count = favorite_count-1 WHERE id=$entry_id");
    }

    public function insert($input, $skip_validation = false)
    {
        $post = array(
            'user_id'    => $input['user_id'],
            'entry_type' => $input['entry_type'],
            'entry_id'   => $input['entry_id']
        );

        if (!$this->if_exists($input)) {
            switch ($input['trend']) {
                case TREND_STAR:
                    $post['star']     = true;
                    $this->add_star($input['entry_id']);
                    break;
                case TREND_FOLLOW:
                    $post['follow']   = true;
                    $this->add_follow($input['entry_id']);
                    break;
                case TREND_FAVORITE:
                    $post['favorite'] = true;
                    $this->add_favorite($input['entry_id']);
                    break;
            }
            parent::insert($post);
            return '+1';
        } else {
            $trendIncrement = '+1';
            $query          = "UPDATE {$this->dbprefix('trends')} SET %s , modified_at='" . date('Y-m-d H:i:s') . "' WHERE user_id ={$this->current_user->id}"
                    . " AND entry_type = '{$input['entry_type']}' AND entry_id = {$input['entry_id']}";
            switch ($input['trend']) {
                case TREND_STAR:
                    $star     = $this->select('star')
                            ->get_by(array('user_id' => $input['user_id'], 'entry_type' => $input['entry_type'], 'entry_id' => $input['entry_id']));
                    $existing = $star->star;
                    if ($existing == 'true') {
                        $trendIncrement = '-1';
                        $this->remove_star($input['entry_id']);
                    } else {
                        $this->add_star($input['entry_id']);
                    }
                    $condition = 'star = (if(star="true","false","true")) ';
                    break;
                case TREND_FOLLOW:
                    $follow    = $this->select('follow')
                            ->get_by(array('user_id' => $input['user_id'], 'entry_type' => $input['entry_type'], 'entry_id' => $input['entry_id']));
                    $existing  = $follow->follow;
                    if ($existing == 'true') {
                        $trendIncrement = '-1';
                        $this->remove_follow($input['entry_id']);
                    } else {
                        $this->add_follow($input['entry_id']);
                    }
                    $condition = 'follow = (if(follow="true","false","true")) ';
                    break;
                case TREND_FAVORITE:
                    $favorite  = $this->select('favorite')
                            ->get_by(array('user_id' => $input['user_id'], 'entry_type' => $input['entry_type'], 'entry_id' => $input['entry_id']));
                    $existing  = $favorite->favorite;
                    if ($existing == 'true') {
                        $trendIncrement = '-1';
                        $this->remove_favorite($input['entry_id']);
                    } else {
                        $this->add_favorite($input['entry_id']);
                    }
                    $condition = 'favorite = (if(favorite="true","false","true")) ';
                    break;
            }
            $this->query(sprintf($query, $condition));
            return $trendIncrement;
        }
    }

    public function if_exists($input)
    {
        unset($input['trend']);
        return (int) $this->count_by($input);
    }

    public function toogle_state()
    {
        
    }

    public function count_star($entry_type = 'event', $entry_id = null, $user_id = null)
    {
        return $this->count_by(array('entry_type' => $entry_type, 'entry_id' => $entry_id, 'star' => 'true'));
    }

    public function count_trend($trend, $entry_type = null, $user_id = null)
    {
        if ($trend == TREND_FOLLOW) {
            $select = 'follow';
        } elseif ($trend == TREND_FAVORITE) {
            $select = 'favorite';
        } elseif ($trend == TREND_STAR) {
            $select = 'star';
        }
        $this->count_by();
    }

    public function get_my_trend($trend, $user, $entry_type , $entry_id)
    {
        $select = '*';
        if ($trend == TREND_FOLLOW) {
            $select = 'follow';
        } elseif ($trend == TREND_FAVORITE) {
            $select = 'favorite';
        } elseif ($trend == TREND_STAR) {
            $select = 'star';
        }
        $array = array('entry_type' => $entry_type, 'user_id' => $user, 'entry_id' => $entry_id);
        return $this->select($select)->get_by($array);
    }

    public function get_trending_events($user_id = null, $type = 'event', $sub_cat_id = null, $limit = null)
    {
        if ($type == 'interest') {
            $cat         = $this->event_categories_m->get_by('slug', 'interest');
            $category_id = $cat->id;
        } else {
            $cat         = $this->event_categories_m->get_by('slug', 'event');
            $category_id = $cat->id;
        }

        $this->db->select('E.*,C.created_on , count(*) as counter')
                ->from('events as E')
                ->join('comments as C', 'E.id = C.entry_id', 'left')
                ->where('(C.created_on BETWEEN DATE_SUB(C.created_on , INTERVAL 5 day) and NOW())');
        if ($user_id != '') {
            $this->db->where('C.user_id', $user_id);
        }

        if ($sub_cat_id != '') {
            $this->db->where('sub_category_id', $sub_cat_id);
        }
        $this->db->where('E.category_id', $category_id);
        $this->db->where('E.published', 1);
        $this->db->group_by('C.entry_id')
                ->order_by('C.created_on', 'DESC');
        if ($limit != '') {
            $this->db->limit($limit);
        }
        return $this->db->get()
                        ->result();
    }

    public function get_trending($user_id = null, $type = 'event', $sub_cat_id = null, $limit = null)
    {
        if ($type == 'interest') {
            $cat         = $this->event_categories_m->get_by('slug', 'interest');
            $category_id = $cat->id;
        } else {
            $cat         = $this->event_categories_m->get_by('slug', 'event');
            $category_id = $cat->id;
        }
        $this->db
                ->order_by('star_count', 'DESC')
                ->order_by('follow_count', 'DESC')
                ->order_by('favorite_count', 'DESC');
        $cond = 'events.id = t.entry_id';
        if ($user_id != '') {
            $cond.=' AND t.user_id = ' . $user_id;
        }
        $this->db->join('trends as t', $cond, 'left');

        if (!is_null($limit)) {
            $this->db->limit($limit);
        }
        if ($sub_cat_id != '') {

            $this->db->where('events.sub_category_id', $sub_cat_id);
        }
        $this->db->where('events.category_id', $category_id);
        $this->db->group_by('events.id');
        $rs = $this->db->get('events')->result();
        return $rs;
    }

    public function get_favorites($user_id = null, $limit = null, $entry_type = 'event', $sub_category = null)
    {
        $sql = "
                        SELECT E.*   
                        FROM {$this->db->dbprefix('events')} AS E
                        WHERE E.id IN (
                                            SELECT entry_id 
                                            FROM {$this->db->dbprefix('trends')} AS T2 
                                            WHERE T2.favorite = 'true'";

        if ($user_id != '') {
            $sql.=" AND T2.user_id = {$user_id}";
        }
        if ($sub_category != '') {
            $sql.=" AND E.sub_category_id='" . $sub_category . "'";
        }
        $sql.=" AND T2.entry_type='" . $entry_type . "'";
        $sql.= " GROUP BY T2.entry_id ";

        $sql.= ")";

        if (!is_null($limit)) {
            $sql.=" LIMIT {$limit}";
        }
        $q = $this->query($sql);

        $result = $q->result();
        return $result;
    }

    /**
     * 
     * @param mixed $user_id
     * @param int $limit
     * @param string $entry_type
     * @param int $sub_category 
     * @return mixed list of events that logged in user's friends following. 
     */
    public function get_follows($user_id = null, $limit = null, $entry_type = 'event', $sub_category = null)
    {
        $sql = "
                        SELECT E.*   
                        FROM {$this->db->dbprefix('events')} AS E
                        WHERE E.id IN (
                                             SELECT entry_id 
                                             FROM {$this->db->dbprefix('trends')} AS T2
                                             
                                             WHERE T2.follow = 'true'";
        if (!empty($user_id)) {
            if (is_array($user_id)) {
                $sql.=" AND T2.user_id IN (" . implode('', $user_id) . ")";
            } else {
                $sql.=" AND T2.user_id = {$user_id}";
            }
        }
        if ($sub_category != '') {
            $sql.=" AND E.sub_category_id='" . $sub_category . "'";
        }
        $sql.=" AND T2.entry_type='" . $entry_type . "')";

        if (!is_null($limit)) {
            $sql.=" LIMIT {$limit}";
        }
        $q = $this->query($sql);

        $result = $q->result();
        return $result;
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
        $this->_get_all_setup();

        $this->db
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

    /**
     * To check if the logged in user already following the event 
     * 
     * @param int $event_id
     * @return boolean
     */
    public function am_i_following($event_id)
    {

        $trend = $this
                ->select('follow')
                ->get_by(
                array(
                    'user_id'  => $this->current_user->id,
                    'entry_id' => $event_id
                )
        );
        return (is_object($trend) and $trend->follow == 'true');
    }

    /**
     * list of all the followers of the events that have been created by the logged in user
     */
    public function get_my_event_followers()
    {
        $this->db
                ->select('P.*,AA.status')
                ->from('events as E')
                ->join('trends as T', 'T.entry_id = E.id', 'left')
                ->join('profiles as P', 'P.user_id = T.user_id', 'left');
        $this->db->set_dbprefix(null);
        $this->db->join('auto_approvals as AA', 'AA.admin_id = E.author', 'left');
        $this->db->set_dbprefix('default_');
        $select = $this->db->where('E.author', $this->current_user->id)
                ->where('T.user_id!=', $this->current_user->id)
                ->where('T.follow', 'true')
                ->group_by('P.user_id')
                ->get()
                ->result_array();
        return $select;
    }
    
    /*
     * fetch list of event and interest that user following
     */
    public function get_followings($user_id)
    {
        return $this->select('entry_id, entry_type')
                ->where_in('entry_type', array('event', 'interest'))
                ->get_many_by(array('user_id' => $user_id, 'follow' => true));
    }

}
