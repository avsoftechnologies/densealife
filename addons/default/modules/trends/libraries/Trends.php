<?php

defined('BASEPATH') OR exit('No direct script access allowed') ;

/**
 * Trends library
 *
 * @author		Phil Sturgeon
 * @author		PyroCMS Dev Team
 * @package		PyroCMS\Core\Modules\Trends\Libraries
 */
class Trends
{

    /**
     * The name of the module in use
     * 
     * @var	string
     */
    protected $module ;

    /**
     * Singular language key
     * 
     * @var	string
     */
    protected $singular ;

    /**
     * Plural language key
     * 
     * @var	string
     */
    protected $plural ;

    /**
     * Entry for this, be it an auto increment id or string
     * 
     * @var	string|int
     */
    protected $entry_id ;

    /**
     * Title of the entry
     * 
     * @var	string
     */
    protected $entry_title ;

    /**
     * What is the URL of this entry?
     * 
     * @var	string
     */
    protected $entry_uri ;

    /**
     * Encrypted hash containing title, singular and plural keys
     * 
     * @var	bool
     */
    protected $entry_hash ;

    /**
     * Comment Count
     *
     * Setting to 0 by default.
     *
     * @var 	int
     */
    protected $count = 0 ;

    CONST TREND_FOLLOW    = 1 ;
    CONST TREND_FAVOURITE = 2 ;
    CONST TREND_STAR      = 3 ;
    CONST TREND_SHARE      = 4 ;
    

    /**
     * Function to display a trend
     *
     * Reference is a actually an object reference, a.k.a. categorization of the trends table rows.
     * The reference id is a further categorization on this. (For example, for example for
     *
     * @param	string	$module		The name of the module in use
     * @param	string	$singular	Singular language key
     * @param	string	$plural		Plural language key
     * @param	string|int	$entry_id	Entry for this, be it an auto increment id or string, or null
     */
    public function __construct($params)
    {
        ci()->load->model('trends/trend_m') ;
        ci()->lang->load('trends/trends') ;
        // This shouldnt be required if static loading was possible, but its not in CI
        if ( is_array($params) ) {
            // Required
            $this->module   = $params['module'] ;
            $this->singular = $params['singular'] ;
            $this->plural   = $params['plural'] ;

            // Overridable
            $this->entry_uri = isset($params['uri']) ? $params['uri'] : uri_string() ;

            // Optional
            isset($params['entry_id']) and $this->entry_id    = $params['entry_id'] ;
            isset($params['entry_title']) and $this->entry_title = $params['entry_title'] ;
        }
    }

    /**
     * Display trends
     *
     * @return	string	Returns the HTML for any existing trends
     */
    public function display()
    {
        // Fetch trends, then process them
        $trends = $this->process(ci()->trend_m->get_by_entry($this->module, $this->singular, $this->entry_id)) ;
        // Return the awesome trends view
        return $this->load_view('display', compact(array( 'trends' ))) ;
    }

    public function display_children($parent_id = null)
    {
        // Fetch trends, then process them
        $trends = $this->process(ci()->trend_m->get_by_entry($this->module, $this->singular, $this->entry_id, true, $parent_id)) ;
        // Return the awesome trends view
        return $this->load_view('display_children', compact(array( 'trends' ))) ;
    }

    public function feeds($limit = null)
    {
        // Fetch trends, then process them
        return $this->process(ci()->trend_m->get_recent($limit)) ;
    }

    /**
     * Display form
     *
     * @return	string	Returns the HTML for the trend submission form
     */
    public function form()
    {
        // Return the awesome trends view
        return $this->load_view('form', array(
                    'module'     => $this->module,
                    'entry_hash' => $this->encode_entry(),
                    'trend'      => ci()->session->flashdata('trend')
                )) ;
    }

    public function follow()
    {
        // Return the awesome trends view
        return $this->load_view('follow', array(
                    'module'       => $this->module,
                    'entry_hash'   => $this->encode_entry(),
                    'trend'        => ci()->session->flashdata('trend'),
                    'is_following' => $this->count(self::TREND_FOLLOW, ci()->current_user->id)
                )) ;
    }

    public function favorite()
    {
        // Return the awesome trends view
        return $this->load_view('favorite', array(
                    'module'       => $this->module,
                    'entry_hash'   => $this->encode_entry(),
                    'trend'        => ci()->session->flashdata('trend'),
                    'is_following' => $this->count(self::TREND_FAVOURITE, ci()->current_user->id)
                )) ;
    }

    public function star($display_count = false)
    {
        // Return the awesome trends view
        return $this->load_view('star', array(
                    'module'       => $this->module,
                    'entry_hash'   => $this->encode_entry(),
                    'trend'        => ci()->session->flashdata('trend'),
                    'is_following' => $this->count(self::TREND_STAR, ci()->current_user->id),
                    'display'      => $display_count
                )) ;
    }
    
    public function get_entry_type($entry_id){
        return 'event'; 
        $rs = ci()->db->select('ec.slug')
                ->from('events as e')
                ->join('event_categories as ec', 'ec.id = e.category_id', 'inner')
                ->where('e.id', $entry_id)
                ->get()
                ->row();
        return $rs->slug;
    }
    
    public function link_favorite($entry_id)
    {
        $user_id = ci()->current_user->id; 
        $data = array('user_id' => $user_id, 
            'entry_type' => $this->get_entry_type($entry_id), 
            'entry_id' => $entry_id,
            'trend' => TREND_FAVORITE);
        $encrypted = ci()->encrypt->encode(serialize($data)) ;
        // Return the awesome trends view
        return $this->load_view('link_favorite', array(
                            'data' => $encrypted,
                            'text' => $this->get_trend_label($user_id, $entry_id, TREND_FAVORITE)
                        )
                );
    }
    public function link_follow($entry_id, $class = null)
    {
        $user_id = ci()->current_user->id; 
        $data = array('user_id' => $user_id, 
            'entry_type' => $this->get_entry_type($entry_id), 
            'entry_id' => $entry_id,
            'trend' => TREND_FOLLOW);
        $encrypted = ci()->encrypt->encode(serialize($data)) ;
        // Return the awesome trends view
        return $this->load_view('link_follow', array(
                            'data' => $encrypted,
                            'text' => $this->get_trend_label($user_id, $entry_id, TREND_FOLLOW),
                            'class' => $class,
                            'entry_id' => $entry_id
                        )
                );
    }
    public function link_star($entry_id, $entry_type = null, $variation = 'icon')
    {
        $user_id = ci()->current_user->id; 
        $data = array('user_id' => $user_id, 
            'entry_type' => ($entry_type!='') ? $entry_type : $this->get_entry_type($entry_id) , 
            'entry_id' => $entry_id,
            'trend' => TREND_STAR);
        $encrypted = ci()->encrypt->encode(serialize($data)) ;
        // Return the awesome trends view
        return $this->load_view('link_star', array(
                            'data' => $encrypted,
                            'text' => $this->get_trend_label($user_id, $entry_id, TREND_STAR, $data['entry_type']),
                            'star_count' => ci()->trend_m->count_star($data['entry_type'], $entry_id),
                            'variation' => $variation,
                            'entry_id' => $entry_id
                        )
                );
    }

    public function get_trend_label($user_id, $entry_id, $trend, $entry_type = 'event'){
        
        $my_trend = ci()->trend_m->get_by(array('user_id' => $user_id, 'entry_id' => $entry_id, 'entry_type' => $entry_type));
        $label = null;
           switch($trend){
               case TREND_FOLLOW:
                   if(isset($my_trend->follow) && $my_trend->follow == 'true'){
                      $label =  'Following';
                   }else{
                       $label = 'Follow';
                   }
                   break;
               case TREND_FAVORITE:
                   if(isset($my_trend->favorite) && $my_trend->favorite =='true'){
                       $label = 'Favorite';
                   }else{
                       $label = 'Add Favorite';
                   }
                   break;
               case TREND_STAR:
              
                   if(!empty($my_trend->star) && $my_trend->star =='true'){
                       $label = 'Unstar';
                   }else{
                       $label = 'Star';
                   }
                   break;
           }
           return $label;
    }
    
    
    public function display_stars()
    {
        return $this->load_view('display_stars', array(
                    'count' => $this->count(self::TREND_STAR)
                )) ;
    }

    /**
     * Count trends
     *
     * @return	int	Return the number of trends for this entry item
     */
    public function countold($trend, $user_id = null)
    {
        $where = array(
            'module'    => $this->module,
            'entry_key' => $this->singular,
            'trend'     => $trend
                ) ;
        if ( !is_null($this->entry_id) ) {
            $where['entry_id'] = $this->entry_id ;
        }
        if ( !is_null($user_id) ) {
            $where['user_id'] = $user_id ;
        }
        ci()->db->where($where)->count_all_results('trends') ;
        return ( int ) ci()->db->where($where)->count_all_results('trends') ;
    }
    
    public function comment_count($trend, $comment_id, $user_id = null)
    {
        $where = array(
            'module'    => $this->module,
            'entry_key' => $this->singular,
            'trend'     => $trend,
            'comment_id' => $comment_id,
            'status' => 'true'
                ) ;
        if ( !is_null($this->entry_id) ) {
            $where['entry_id'] = $this->entry_id ;
        }
        if ( !is_null($user_id) ) {
            $where['user_id'] = $user_id ;
        }
        return ( int ) ci()->db->where($where)->count_all_results('comment_trends') ;
    }
    public function count_followers($entry_id, $entry_type = 'event')
    {
        $where['entry_id'] = $entry_id ;
        $where['entry_type'] = $entry_type ;
        $where['follow'] = 'true';
        return ci()->db->where($where)->count_all_results('trends') ;
    }
    public function count($trend, $entry_type = null, $user_id = null)
    {
        $where = array(
            'module'    => $this->module,
            'entry_key' => $this->singular,
            'trend'     => $trend
        ) ;
        if ( !is_null($this->entry_id) ) {
            $where['entry_id'] = $this->entry_id ;
        }
        if ( !is_null($user_id) ) {
            $where['user_id'] = $user_id ;
        }
        if ( !is_null($entry_type) ) {
            $where['entry_type'] = $entry_type ;
        }
        return ( int ) ci()->db->where($where)->count_all_results('trends') ;
    }

    public function count_new($trend)
    {
        $where = array(
            'module'                             => $this->module,
            'entry_key'                          => $this->singular,
            'entry_id'                           => $this->entry_id,
            'trend'                              => $trend,
            'DATEDIFF( NOW( ) , created_on ) <=' => 7
                ) ;

        return ( int ) ci()->db->where($where)->count_all_results('trends') ;
    }

    /**
     * Count trends as string
     *
     * @return	string 	Language string with the total in it
     */
    public function count_string($trend_count = null)
    {
        $total = ($trend_count) ? $trend_count : $this->count ;

        switch ( $total ) {
            case 0:
                $line = 'none' ;
                break ;
            case 1:
                $line = 'singular' ;
                break ;
            default:
                $line = 'plural' ;
        }

        return sprintf(lang('trends:counter_' . $line . '_label'), $total) ;
    }

    /**
     * Function to process the items in an X amount of trends
     *
     * @param array $trends The trends to process
     * @return array
     */
    public function process($trends)
    {
        // Remember which modules have been loaded
        static $modules = array() ;

        foreach ( $trends as &$trend ) {
            // Override specified website if they are a user
            if ( $trend->user_id and Settings::get('enable_profiles') ) {
                $trend->website = 'user/' . $trend->user_name ;
            }

            // We only want to load a lang file once
            if ( !isset($modules[$trend->module]) ) {
                if ( ci()->module_m->exists($trend->module) ) {
                    ci()->lang->load("{$trend->module}/{$trend->module}") ;

                    $modules[$trend->module] = true ;
                }
                // If module doesn't exist (for whatever reason) then sssh!
                else {
                    $modules[$trend->module] = false ;
                }
            }

            $trend->singular = lang($trend->entry_key) ? lang($trend->entry_key) : humanize($trend->entry_key) ;
            $trend->plural   = lang($trend->entry_plural) ? lang($trend->entry_plural) : humanize($trend->entry_plural) ;

            // work out who did the trending
            if ( $trend->user_id > 0 ) {
                $trend->user_name = anchor('admin/users/edit/' . $trend->user_id, $trend->user_name) ;
            }

            // Security: Escape any Lex tags
            foreach ( $trend as $field => $value ) {
                $trend->{$field} = escape_tags($value) ;
            }
        }

        return $trends ;
    }

    /**
     * Load View
     *
     * @return	string	HTML of the trends and form
     */
    protected function load_view($view, $data)
    {
        $ext = pathinfo($view, PATHINFO_EXTENSION) ? '' : '.php' ;

        if ( file_exists(ci()->template->get_views_path() . 'modules/trends/' . $view . $ext) ) {
            // look in the theme for overloaded views
            $path = ci()->template->get_views_path() . 'modules/trends/' ;
        } else {
            // or look in the module
            list($path, $view) = Modules::find($view, 'trends', 'views/') ;
        }

        // add this view location to the array
        ci()->load->set_view_path($path) ;
        ci()->load->vars($data) ;

        return ci()->load->_ci_load(array( '_ci_view' => $view, '_ci_return' => true )) ;
    }

    /**
     * Encode Entry
     *
     * @return	string	Return a hash of entry details, so we can send it via a form safely.
     */
    protected function encode_entry($array = array())
    {
        $default = array(
            'title'    => $this->entry_title,
            'uri'      => $this->entry_uri,
            'singular' => $this->singular,
            'plural'   => $this->plural,
        );
        return ci()->encrypt->encode(serialize(array_merge($default, $array))) ;
    }

    public function get_popular_entry($limit = null)
    {
        //$rs = ci()->trend_m->get_trending($limit); 
        $rs = ci()->trend_m->get_favorites($limit); 
        echo '<pre>'; 
        print_r($rs); exit; 
        return $rs; 
    }
    
    public function get_trending($user_id= null, $type = 'event', $sub_category_id = null, $limit = null)
    {
        return ci()->trend_m->get_trending_events($user_id, $type,$sub_category_id, $limit); 
    }
    
    public function get_favorites($user_id = null,$type = 'event',$sub_cat_id=null, $limit = null)
    {
        return ci()->trend_m->get_favorites($user_id,$limit,$type, $sub_cat_id); 
    }
    
    public function get_followers($entry_id,$is_friend = true,$mutual_friends = true, $limit=null){
        ci()->db->select('p.user_id,p.display_name,p.address_line1, p.address_line2, p.address_line3, u.username')
                ->distinct()
                ->from('profiles as p')
                ->join('trends as t','p.user_id = t.user_id','left')
                ->join('users as u','p.user_id = u.id','inner')
                ->where('t.entry_id', $entry_id)
                ->where('t.entry_type', 'event')
                ->where('t.follow','true')
                ->where('p.user_id!=', ci()->current_user->id);
        if(!is_null($limit)){
            ci()->db->limit($limit);
        }
        $followers = ci()->db->get()->result() ;
        
        ci()->load->library('friend/friend');
         
            foreach($followers as &$follower){
                if($follower->user_id != ci()->current_user->id){
                    if($is_friend){
                        $friend = ci()->friend->is_friend($follower->user_id);
                        $follower->is_friend = (isset($friend->status) && $friend->status == 'accepted'); 
                        $follower->status_label = isset($friend->status_label) ? $friend->status_label : 'Add Friend';
                    }
                    if($mutual_friends){
                        $follower->mutual_friends = ci()->friend->get_mutual_friends($follower->user_id);
                    }
                }
            }
        
        
        return $followers;
    }
   
}
