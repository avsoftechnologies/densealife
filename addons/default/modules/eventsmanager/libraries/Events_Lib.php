<?php

/**
 * Description of Events_Lib
 *
 * @author Ankit Vishwakarma <ankitvishwakarma@sify.com>
 */
class Events_Lib
{

    public function __construct()
    {
        ci()->load->model('eventsmanager_m');
    }
    public function count_followers($slug, $follow_type, $user = null)
    {
        ci()->db->where(array(
            'event_slug' => $slug,
            'follow'     => $follow_type,
        )) ;
        if ( !is_null($user) ) {
            ci()->db->where(array( 'user_id' => $user )) ;
        }
        $result = ( int ) ci()->db->count_all_results('event_followers') ;
        return $result ;
    }

    public function get_trending($limit = null)
    {
        ci()->db->select('e.*, count(follow) as follows')
                ->from('events as e')
                ->join('event_followers as ef', 'ef.event_slug = e.slug', 'left')
                ->where('e.published', 1)
                ->group_by('e.slug')
                ->order_by('follows', 'DESC')
                ->order_by('e.title', 'ASC') ;
        if ( !is_null($limit) ) {
            ci()->db->limit($limit) ;
        }
        return ci()->db->get()
                        ->result() ;
    }
    
    
    public function get_upcoming($user_id = null, $type= 'event', $sub_cat_id = null,  $limit = null)
    {
        return ci()->eventsmanager_m->get_upcoming($user_id,$type, $sub_cat_id, $limit) ;
    }
    
}
