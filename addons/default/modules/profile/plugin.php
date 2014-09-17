<?php

defined('BASEPATH') or exit('No direct script access allowed') ;

/**
 * Friends Plugin
 *
 * @author   PyroCMS Dev Team
 * @package  PyroCMS\Core\Modules\Comments\Plugins
 */
class Plugin_Profile extends Plugin
{

    public $version     = '1.0.0' ;
    public $name        = array(
        'en' => 'Follower',
    ) ;
    public $description = array(
        'en' => 'Plugin for Followers',
    ) ;
    
    public function __construct()
    {
        $this->load->model('profile/follower_m');
        $this->load->model('profile/social_m');
    }

    public function count_followers()
    {
        $user_id = $this->attribute('user_id', $this->current_user->id);
        return $this->follower_m->count_followers($user_id);
    }
    
    public function count_following()
    {
        $user_id = $this->attribute('user_id', $this->current_user->id);
        return $this->follower_m->count_followings($user_id);
    }
    
    public function link()
    {
        $user_id = $this->attribute('user_id');
        $type = $this->attribute('type');
        $links = $this->social_m->get_by(array('user_id' => $user_id));
        
        if(!empty($links) and array_key_exists($type, $links)){
            return $links->$type;
        }
    }
    
    public function right_side_bar_blocks()
    {
        $this->load->model('eventsmanager/eventsmanager_m');
        $user_id = $this->attribute('user_id');
        $type = $this->attribute('type');
        switch($type) {
            case 'event':
            case 'interest':
                $items = $this->eventsmanager_m->get_following_entry($user_id, $type, 6);
                $count = $this->eventsmanager_m->get_following_entry_count($user_id, $type);
                if($type=='event') {
                    $title = 'Events';
                    $hashValue = 'events';
                }else{
                    $title = 'Interests';
                    $hashValue = 'interests';
                }
                break;
            case 'favorite':
                $items = $this->eventsmanager_m->get_favorite_entry($user_id, 6);
                $count = $this->eventsmanager_m->get_favorite_entry_count($user_id); 
                $title = 'Favorites';
                $hashValue = 'favorites';
                break;
            
            
        }
        if($count) {
            return load_view(
                'eventsmanager',
                'layout/user/entry_block',
                array(
                    'items' => $items,
                    'count' => $count,
                    'title' => $title,
                    'hashValue' => $hashValue
                )
            );
        }
    }
    
    /**
     * renders html block for friend list
     * @param type $user_id
     * @return type
     */
    public function block_friends($user_id = null)
    {
        $user_id      = $this->attribute('user_id', $user_id);
        $layout       = $this->attribute('layout', 'user');
        $this->load->model('friend/friend_m');
        $friends      = $this->friend_m->get_friends($user_id);
        $friend_count = count($friends);
        if ($friend_count) {
            switch ($layout) {
                case 'user':
                    return load_view(
                            'eventsmanager', 'layout/user/friend_block', array(
                        'friends'   => $friends,
                        'count'     => $friend_count,
                        'title'     => 'Friends',
                        'hashValue' => 'friends'
                            )
                    );
                case 'densealife':
                    return load_view(
                            'profile', 'layout/densealife/friends_block', array(
                        'friends'   => $friends,
                        'count'     => $friend_count,
                        'title'     => 'Friends',
                        'hashValue' => 'friends'
                            )
                    );
            }
        }
    }

    public function friend_count($user_id = null)
    {
        $user_id = $this->attribute('user_id', $user_id);
        return count($this->friends($user_id));
    }

}

/* End of file plugin.php */
