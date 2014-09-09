<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Plugin_Button extends Plugin
{

    public $version     = '1.0.0';
    public $name        = array(
        'en' => 'Button',
    );
    public $description = array(
        'en' => 'Button Plugin.',
    );

    /**
     * 
     * @return type
     */
    function friend()
    {
        $user_id = $this->attribute('user_id');
        $current_user_id = $this->attribute('current_user_id',null);
        if ( $current_user_id != ci()->current_user->id || $current_user_id =='') {
            $current_user_id = ci()->current_user->id;
        }
        ci()->load->model('friend/friend_m');
        $friendship = ci()->friend_m->is_friend($user_id, $current_user_id);


        if ( $friendship ):
            switch ( $friendship->status ) {
                case 'accepted':
                    return'<button class="common right btn_add_friend_' . $user_id . '" onclick="friend.unfriend(' . $user_id . ');" onmouseover="$(this).text(\'Unfriend\')" onmouseout="$(this).text(\'Friends\')">Friends</button>';
                    break;
                case 'initiated':
                    return '<button class="common right btn_add_friend_' . $user_id . '" onclick="friend.accept(' . $user_id . ');">' . $friendship->status_label . '</button>';
                    break;
                default:
                    return '<button class="common right btn_add_friend_' . $user_id . '" onclick="friend.add(' . $user_id . ');">+ Add Friends</button>';
                    break;
            } elseif ( $user_id != ci()->current_user->id ):
            return '<button class="common right btn_add_friend_' . $user_id . '" onclick="friend.add(' . $user_id . ');">+ Add Friends</button>';
        endif;
    }
    
    /**
     * 
     * @return type
     */
    function follow()
    {
        $user_id         = $this->attribute('user_id');
        $current_user_id = $this->attribute('current_user_id', null);
        if ( $current_user_id != ci()->current_user->id || $current_user_id == '' ) {
            $current_user_id = ci()->current_user->id;
        }
        ci()->load->model('profile/follower_m');
        $follower = ci()->follower_m->is_follower($user_id, $current_user_id);

        if ( $follower === false ) {
            return '<button class="btn_follow_' . $user_id . ' common" onclick="friend.follow(' . $user_id . ');">Follow</button>';
        } else {
            return '<button class="btn_follow_' . $user_id . ' common" onclick="friend.follow(' . $user_id . ');">Following</button>';
        }
    }
    
    /**
     * 
     */
    function follow_event()
    {
        $this->load->library('trends/trends');
        $event_id         = $this->attribute('event_id');
        $class            = $this->attribute('class','common ctrl_trend');
        return $this->trends->link_follow($event_id, $class);
        
    }
    
    function star_event()
    {
        $this->load->library('trends/trends');
        $event_id       = $this->attribute('event_id');
        $variation      = $this->attribute('variation','icon');
        $entry_type     = $this->attribute('type','event');
        return $this->trends->link_star($event_id, $entry_type, $variation);
        
    }
    
    function favorite_event()
    {
        $this->load->library('trends/trends');
        $event_id         = $this->attribute('event_id');
        return $this->trends->link_favorite($event_id);
    }
    
    function invite_friend()
    {
        $this->load->library('friend/friend');
        $event_id         = $this->attribute('eid');
        $friend_id         = $this->attribute('fid');
        return $this->friend->link_invite($event_id, $friend_id);
    }
}