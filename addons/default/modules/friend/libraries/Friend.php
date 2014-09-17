<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Trends library
 *
 * @author		Phil Sturgeon
 * @author		PyroCMS Dev Team
 * @package		PyroCMS\Core\Modules\Trends\Libraries
 */
class Friend
{

    CONST AWAITING_RESPONSE = 'awaiting_response';
    CONST REQUEST_SENT      = 'request_sent';

    public function __construct()
    {
        ci()->load->model('friend/friend_m');
    }

    public function friend_suggestions($limit = null)
    {
        return ci()->friend_m->get_friend_suggestions($limit);
    }

    public function status_label_mapping($status)
    {
        return ci()->friend_m->get_status_label_mapping($status);
    }

    public function is_friend($who, $whose = null)
    {
        return ci()->friend_m->is_friend($who, $whose);
    }

    public function get_follower_friends($event_id, $user_id)
    {
        return ci()->friend_m->get_follower_friends($event_id, $user_id);
    }

    public function get_mutual_friends($user_id)
    {
        return ci()->friend_m->get_mutual_friends($user_id);
    }

    public function get_events($user_id, $type='event',  $limit = null)
    {
        return ci()->friend_m->get_friend_recently_followed_entry($user_id, $type, $limit);
    }

    public function link_invite($entry_id, $friend_id)
    {
        return load_view('friend', 'link_invite', array(
            'friend_id' => $friend_id,
            'entry_id'  => $entry_id)
        );
    }

}
