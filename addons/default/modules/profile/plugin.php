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
    
}

/* End of file plugin.php */
