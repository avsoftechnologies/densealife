<?php

if ( !defined('BASEPATH') )
    exit('No direct script access allowed') ;

/**
 * Events Manager plugin
 *
 * @author 		Ankit Vishwakarma <ankitvishwakarma@sify.com>
 * @website		http://www.ankitvishwakarma.com
 * @package 	PyroCMS
 * @subpackage 	Events Manager plugin
 */
class Plugin_Eventsmanager extends Plugin
{

    private $places = array( 'core'          => APPPATH,
        'site_addons'   => ADDONPATH,
        'shared_addons' => SHARED_ADDONPATH
            ) ;
    private $module = "eventsmanager" ;

    /**
     * Retrieve the full path to Events Manager module directory
     * With a trailing slash
     * 
     * Usage:
     * {{ eventsmanager:path }} Outputs : addons/default/modules/eventsmanager/
     *
     * @return string
     */
    public function path()
    {
        foreach ( $this->places as $place )
            if ( is_dir($place . 'modules/' . $this->module) )
                return $place . 'modules/' . $this->module . '/' ;
        return false ;
    }

    public function followers()
    {
        $slug        = $this->attribute('slug') ;
        $follow_type = $this->attribute('type') ;

        $this->load->library('eventsmanager/events_lib') ;
        return $this->events_lib->count_followers($slug, $follow_type) ;
    }

    public function trending()
    {
        $limit  = $this->attribute('limit', null) ;
        $user_id  = $this->attribute('user_id',null) ;
        $type  = $this->attribute('type','event') ;
        $sub_cat_id  = $this->attribute('sub_type',null) ;
        $this->load->library('trends/Trends') ;

        return $this->trends->get_trending($user_id, $type, $sub_cat_id , $limit) ;
    }
    
    public function favorites($user_id = null)
    {
        $limit  = $this->attribute('limit', null) ;
        $user_id  = $this->attribute('user_id',$user_id) ;
        $type =  $this->attribute('type', 'event') ;
        $sub_cat_id =  $this->attribute('sub_cat_id', null) ;
        if(is_null($user_id)){
            $user_id = $this->current_user->id; 
        }
        $this->load->model('trends/trend_m');
        $favorites = $this->trend_m->get_favorites($user_id, $limit, $type, $sub_cat_id);
        return $favorites;
    }
    
    public function count_favorites()
    {
       $user_id = $this->attribute('user_id'); 
       $this->load->model('trends/trend_m');
       $favorites = $this->trend_m->get_favorites($user_id);
       return count($favorites);
    }

    public function upcoming()
    {
        $limit = $this->attribute('limit', null) ;
         $user  = $this->attribute('user', null) ;
         $type  = $this->attribute('type', 'event') ;
         $sub_cat_id = $this->attribute('sub_cat_id',null) ;
        $user_id = null;
        if(  is_logged_in() && !is_null($user)){
            $user_id = !isset($user->id) ? ci()->current_user->id : $user->id;
        }

        // load the eventsmanager module's model
        class_exists('Eventsmanager_m') OR $this->load->model('eventsmanager/eventsmanager_m') ;


        // retrieve the records using the blog module's model
        return $this->eventsmanager_m->get_upcoming($user_id, $type, $sub_cat_id, $limit) ;
    }

    public function thumb()
    {
        $thumbnail_name = $this->attribute('name') ;
        $height         = $this->attribute('height', '229') ;
        $width          = $this->attribute('width', '163') ;
        if ( is_file(UPLOAD_PATH . 'files/' . $thumbnail_name) ) {
            return img(array( 'src' => UPLOAD_PATH . 'files/' . $thumbnail_name, 'height' => $height, 'width' => $width )) ;
        }else{
            return img(array( 'src' => base_url('assets/images/no-thumbnail.jpg'), 'height' => $height, 'width' => $width )) ;
        }
    }
    
    
    public function get_all_events($user_id = null, $entry_type = 'event')
    {
        $user_id = $this->attribute('user_id', $user_id);
        $entry_type = $this->attribute('entry_type', $entry_type);
        $limit = $this->attribute('limit', null);
        
        class_exists('Eventsmanager_m') OR $this->load->model('eventsmanager/eventsmanager_m') ;
        return $this->eventsmanager_m->get_all_events($user_id,$entry_type,  $limit);
        
    }
    
    public function count_events()
    {
        $user_id = $this->attribute('user_id',null);
        $entry_type = $this->attribute('entry_type','event');
        return count($this->get_all_events($user_id, $entry_type));
    }
    
    public function get_all_interests($user_id = null, $entry_type = 'interest')
    {
        $user_id = $this->attribute('user_id', $user_id);
        $entry_type = $this->attribute('entry_type','interest');
        $limit = $this->attribute('limit', null);
        $this->get_all_events($user_id, $entry_type, $limit);
        
        
    }
    
     public function count_interests()
    {
        $user_id = $this->attribute('user_id',null);
        $entry_type = $this->attribute('entry_type','interest');
        return count($this->get_all_events($user_id, $entry_type));
    }

}

/* End of file plugin.php */