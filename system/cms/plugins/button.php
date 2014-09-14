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
    
    CONST FRIEND_STATUS_INITIATED = 'initiated'; 
    CONST FRIEND_STATUS_ACCEPTED = 'accepted';
    CONST FRIEND_STATUS_REJECTED = 'rejected';
    
    private $_button_label = '+ Add Friend';
    
    private function _set_label($label)
    {
        $this->_button_label = $label; 
    }
    
    private function _get_button_label($friendship)
    {
        if(empty($friendship->status_label)) {
           return $this->_button_label; 
        }
        $this->_button_label = $friendship->status_label; 
        return $this->_button_label; 
    }
    
    private function _onmouseout($friendship)
    {
        $action = '';
        switch($friendship->status) {
            case self::FRIEND_STATUS_INITIATED:
                if($this->current_user->id == $friendship->sender) {
                    $action.="$(this).text('Awaiting Response')";
                } 
                break;
            
            case self::FRIEND_STATUS_ACCEPTED:
                $action.="$(this).text('Friends');";
                break;
            
        }
        return $action; 
    }
    private function _onmouseover($friendship)
    {
        $action = '';
        switch($friendship->status) {
            case self::FRIEND_STATUS_INITIATED:
                if($this->current_user->id == $friendship->sender) {
                    $action.="$(this).text('Cancel Request')";
                } 
                break;
                
            case self::FRIEND_STATUS_ACCEPTED:
                $action.= "$(this).text('Unfriend');";
                break;
            
        }
        return $action; 
    }
    
    private function _onclick($friendship)
    {
        $action = '';
        switch($friendship->status) {
            case self::FRIEND_STATUS_INITIATED:
                if($this->current_user->id == $friendship->sender) {
                    $action.="friend.cancel_request($friendship->receiver)";
                } else{
                    
                }
                break;
            
            case self::FRIEND_STATUS_ACCEPTED:
                 $action.="friend.unfriend($friendship->receiver)";
                break;
            
            default:
               $action.="friend.add('" . $friendship->receiver . "');";
                break;
            
        }
        return $action;
    }
    
    private function _button($friendship, $click = true, $mouseover = true, $mouseout = true)
    {
        $button = '<button class="common right btn_add_friend_' . $friendship->receiver . '" ';
        if ($click) {
            $button.= 'onclick="' . $this->_onclick($friendship) . '" ';
        }
        if ($mouseover and $friendship->status!='unknown') {
            $button.= 'onmouseover="' . $this->_onmouseover($friendship) . '" ';
        }
        if ($mouseout) {
            $button.= 'onmouseout="' . $this->_onmouseout($friendship) . '" ';
        }

        $button.= '>';
        $button.= $this->_get_button_label($friendship);
        $button.= '</button>';
        
        return $button;
    }

    /**
     * 
     * @return type
     */
    function friend()
    {
        $user_id = $this->attribute('user_id'); // user id whose profile is being visited
        $current_user_id = ci()->current_user->id; // logged in user
        ci()->load->model('friend/friend_m');
        $friendship = ci()->friend_m->is_friend($user_id, $current_user_id);
        if(empty($friendship))
        {
            $friendship               = new stdClass();
            $friendship->status       = 'unknown';
            $friendship->sender       = $current_user_id;
            $friendship->receiver     = $user_id;
            $friendship->status_label = $this->_get_button_label();
        }
        //p($friendship);
        return $this->_button($friendship);
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
        $reload            = $this->attribute('reload', false);
        
        return $this->trends->button_follow($event_id, 'event', $class,$reload);
        
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