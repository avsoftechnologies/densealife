<?php

defined('BASEPATH') or exit('No direct script access allowed') ;

/**
 * Trends Plugin
 *
 * @author   PyroCMS Dev Team
 * @package  PyroCMS\Core\Modules\Comments\Plugins
 */
class Plugin_Trends extends Plugin
{

    public $version     = '1.0.0' ;
    public $name        = array(
        'en' => 'Trends',
    ) ;
    public $description = array(
        'en' => 'Display information about site trends.',
    ) ;

    /**
     * Returns a PluginDoc array that PyroCMS uses 
     * to build the reference in the admin panel
     *
     * All options are listed here but refer 
     * to the Blog plugin for a larger example
     *
     * @todo fill the  array with details about this plugin, then uncomment the return value.
     *
     * @return array
     */
    public function _self_doc()
    {
        $info = array(
            'count' => array( // the name of the method you are documenting
                'description' => array( // a single sentence to explain the purpose of this method
                    'en' => 'Display the number of trends for the specified item.'
                ),
                'single'      => true, // will it work as a single tag?
                'double'      => false, // how about as a double tag?
                'variables'   => '', // list all variables available inside the double tag. Separate them|like|this
                'attributes'  => array(
                    'entry_id'  => array( // this is the order-dir="asc" attribute
                        'type'     => 'number|text', // Can be: slug, number, flag, text, array, any.
                        'flags'    => '',
                        'default'  => '0', // attribute defaults to this if no value is given
                        'required' => true, // is this attribute required?
                    ),
                    'entry_key' => array(
                        'type'     => 'text|lang',
                        'flags'    => '',
                        'default'  => '',
                        'required' => true,
                    ),
                    'module'    => array(
                        'type'     => 'slug',
                        'flags'    => '',
                        'default'  => 'current module',
                        'required' => false,
                    ),
                ),
            ), // end first method
        ) ;

        return $info ;
    }

    /**
     * Count
     *
     * Usage:
     * {{ comments:count entry_id=page:id entry_key="pages:page" [module="pages"] }}
     *
     * @param array
     * @return array
     */
    public function count()
    {
        $entry_id  = $this->attribute('entry_id', $this->attribute('item_id')) ;
        $entry_key = $this->attribute('entry_key') ;
        $module    = $this->attribute('module', $this->module) ;

        $this->load->library('trends/trends', array(
            'entry_id' => $entry_id,
            'singular' => $entry_key,
            'module'   => $module
                )
        ) ;

        return $this->trends->count() ;
    }

    /**
     * Count and return a translated string
     *
     * Usage:
     * {{ comments:count_string entry_id=page:id entry_key="pages:page" [module="pages"] }}
     *
     * @param array
     * @return array
     */
    public function count_string()
    {
        // Are we passing a number directly?
        if ( $comment_count = $this->attribute('count') ) {
            $this->load->library('comments/comments') ;
            return $this->comments->count_string($comment_count) ;
        }

        $entry_id     = $this->attribute('entry_id', $this->attribute('item_id')) ;
        $entry_key    = $this->attribute('entry_key') ;
        $entry_plural = $this->attribute('entry_plural') ;
        $module       = $this->attribute('module', $this->module) ;

        $this->load->library('comments/comments', array(
            'entry_id' => $entry_id,
            'singular' => $entry_key,
            'plural'   => $entry_plural,
            'module'   => $module
                )
        ) ;

        return $this->comments->count_string() ;
    }

    /**
     * Display
     *
     * Usage:
     * {{ comments:display entry_id=page:id entry_key="pages:page" [module="pages"] }}
     *
     * @param array
     * @return array
     */
    public function display()
    {
        $entry_id  = $this->attribute('entry_id', $this->attribute('item_id')) ;
        $entry_key = $this->attribute('entry_key') ;
        $module    = $this->attribute('module', $this->module) ;

        $this->load->library('comments/comments', array(
            'entry_id' => $entry_id,
            'singular' => $entry_key,
            'module'   => $module
                )
        ) ;

        return $this->comments->display() ;
    }

    public function popular()
    {
        $limit  = $this->attribute('limit', null) ;
        $this->load->library('trends/Trends', array(
            'module'   => 'eventsmanager',
            'singular' => 'eventsmanager:event',
            'plural'   => 'eventsmanager:events' )) ;
        $result = $this->trends->get_popular_entry($limit) ;
        foreach ( $result as &$event ) {

            $event->stars   = $this->trends->count(Trends::TREND_STAR) ;
            $event->follows = $this->trends->count(Trends::TREND_FOLLOW) ;
            $event->favs    = $this->trends->count(Trends::TREND_FAVOURITE) ;
        }

        return $result ;
    }
    
    public function followers()
    {
        $limit  = $this->attribute('limit', null) ;
        $entry_id = $this->attribute('entry_id');
        $this->load->library('trends/Trends', array(
            'module'   => 'eventsmanager',
            'singular' => 'eventsmanager:event',
            'plural'   => 'eventsmanager:events',
            'entry_id'=> $entry_id)) ;
        $result = $this->trends->get_followers($entry_id, $limit) ;
        return $result ;
    }
    
    public function followers_count()
    {
        $entry_id = $this->attribute('entry_id');
        $this->load->library('trends/Trends');
        return $this->trends->count_followers($entry_id);
    }
    
    public function favorites()
    {
        $this->load->library('trends/Trends');
        $user_id = $this->attribute('user_id');
        $favorites = $this->trends->get_favorites($user_id);
        return $favorites; 
    }

    public function link_follow()
    {
        $entry_id = $this->attribute('entry_id');
        $this->load->library('trends/Trends');
        return $this->trends->link_follow($entry_id);
    }
    
    public function link_star()
    {
        $post_id = $this->attribute('post_id');
        $this->load->library('comments/Comments');
        return $this->comments->link_star($post_id);
    }

}

/* End of file plugin.php */
