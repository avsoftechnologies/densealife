<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Comments library
 *
 * @author		Ankit Vishwakarma
 */
class Comments
{

    /**
     * The name of the module in use
     * 
     * @var	string
     */
    protected $module;

    /**
     * Singular language key
     * 
     * @var	string
     */
    protected $singular;

    /**
     * Plural language key
     * 
     * @var	string
     */
    protected $plural;

    /**
     * Entry for this, be it an auto increment id or string
     * 
     * @var	string|int
     */
    protected $entry_id;

    /**
     * Title of the entry
     * 
     * @var	string
     */
    protected $entry_title;

    /**
     * What is the URL of this entry?
     * 
     * @var	string
     */
    protected $entry_uri;

    /**
     * Encrypted hash containing title, singular and plural keys
     * 
     * @var	bool
     */
    protected $entry_hash;

    /**
     * Comment Count
     *
     * Setting to 0 by default.
     *
     * @var 	int
     */
    protected $count = 0;

    /**
     * number of comments on any post visible to any user when lands on a page. 
     */
    const LIMIT_POST_COMMENTS = 5;

    /**
     * Function to display a comment
     *
     * Reference is a actually an object reference, a.k.a. categorization of the comments table rows.
     * The reference id is a further categorization on this. (For example, for example for
     *
     * @param	string	$module		The name of the module in use
     * @param	string	$singular	Singular language key
     * @param	string	$plural		Plural language key
     * @param	string|int	$entry_id	Entry for this, be it an auto increment id or string, or null
     */
    public function __construct($params)
    {
        ci()->load->model('comments/comment_m');
        ci()->load->model('comments/comment_blacklists_m');
        ci()->lang->load('comments/comments');
        
        ci()->load->library('trends/trends');

        // This shouldnt be required if static loading was possible, but its not in CI
        if (is_array($params)) {
            // Required
            $this->module   = $params['module'];
            $this->singular = $params['singular'];
            $this->plural   = $params['plural'];

            // Overridable
            $this->entry_uri = isset($params['uri']) ? $params['uri'] : uri_string();

            // Optional
            isset($params['entry_id']) and $this->entry_id    = $params['entry_id'];
            isset($params['entry_title']) and $this->entry_title = $params['entry_title'];
        }
    }

    public function link_star($entry_id, $entry_type = 'comment')
    {
        return ci()->trends->link_star($entry_id, $entry_type);
    }

    /**
     * this function will show the entire posts that are posted in my wall 
     * by me me or by another
     */
    public function display_wall_posts()
    {
        
    }

    public function wall_post_comments()
    {
        
    }

    public function count_post_comments($post_id)
    {
        return ci()->comment_m->from('comments')->where('is_active', 1)->count_by('parent_id', $post_id);
    }

    /**
     * Display comments
     *
     * @return	string	Returns the HTML for any existing comments
     */
    public function display($allow_comment = true, $blacklisted = false)
    {
        // Fetch comments, then process them
        $comments = $this->process(ci()->comment_m->get_comment_by_event($this->entry_id));
        // Return the awesome comments view
        return $this->load_view('display_my_comments', compact(array('comments')) + array('allowcomment' => $allow_comment, 'blacklisted' => $blacklisted));
    }
    
    public function display_pending()
    {
        $comments = $this->process(ci()->comment_m->post_awaiting_approval($this->entry_id));
        // Return the awesome comments view
        return $this->load_view('display_my_comments', compact(array('comments')) + array('allowcomment' => false, 'blacklisted' => false));
    }

    public function display_my_comments($user = null, $allow_comment = true, $blacklisted = false)
    {
        $user_id  = !isset($user->id) ? ci()->current_user->id : $user->id;
        ci()->load->library('trends/trends');
        // Fetch comments, then process them
        $comments = $this->process(ci()->comment_m->get_by_user($user_id));
        // Return the awesome comments view
        return $this->load_view('display_my_comments', compact(array('comments')) + array('allowcomment' => $allow_comment,'blacklisted' => $blacklisted));
    }

    public function display_children($parent_id = null)
    {
        // Fetch comments, then process them
        $comments = $this->process(ci()->comment_m->get_by_entry($this->module, $this->singular, $this->entry_id, true, $parent_id));
        // Return the awesome comments view
        return $this->load_view('display_children', compact(array('comments')));
    }

    public function display_my_children($parent_id = null, $is_main_post = true)
    {
        // Fetch comments, then process them
        $comments = $this->process(ci()->comment_m->get_by_user(ci()->current_user->id, $parent_id, $is_main_post));
        // Return the awesome comments view
        return $this->load_view('display_children', compact(array('comments')));
    }

    public function feeds($limit = null)
    {
        // Fetch comments, then process them
        return $this->process(ci()->comment_m->get_recent($limit));
    }

    /**
     * Display form
     *
     * @return	string	Returns the HTML for the comment submission form
     */
    public function form($parent_id = null)
    {
        // Return the awesome comments view
        return $this->load_view('form', array(
                    'module'     => $this->module,
                    'entry_hash' => $this->encode_entry(),
                    'comment'    => ci()->session->flashdata('comment'),
                    'parent_id'  => $parent_id
        ));
    }

    public function form_media()
    {
        // Return the awesome comments view
        return $this->load_view('form_media', array(
                    'module'     => $this->module,
                    'entry_hash' => $this->encode_entry(),
                    'comment'    => ci()->session->flashdata('comment'),
                    'parent_id'  => null
        ));
    }

    /**
     * Count comments
     *
     * @return	int	Return the number of comments for this entry item
     */
    public function count()
    {
        return (int) ci()->db->where(array(
                    'module'    => $this->module,
                    'entry_key' => $this->singular,
                    'entry_id'  => $this->entry_id,
                    'is_active' => true,
                ))->count_all_results('comments');
    }

    /**
     * Count comments as string
     *
     * @return	string 	Language string with the total in it
     */
    public function count_string($comment_count = null)
    {
        $total = ($comment_count) ? $comment_count : $this->count;

        switch ($total) {
            case 0:
                $line = 'none';
                break;
            case 1:
                $line = 'singular';
                break;
            default:
                $line = 'plural';
        }

        return sprintf(lang('comments:counter_' . $line . '_label'), $total);
    }

    /**
     * Function to process the items in an X amount of comments
     *
     * @param array $comments The comments to process
     * @return array
     */
    public function process($comments)
    {
        // Remember which modules have been loaded
        static $modules = array();

        foreach ($comments as &$comment) {
            // Override specified website if they are a user
            if ($comment->user_id and Settings::get('enable_profiles')) {
                $comment->website = 'user/' . $comment->user_name;
            }

            // We only want to load a lang file once
            if (!isset($modules[$comment->module])) {
                if (ci()->module_m->exists($comment->module)) {
                    ci()->lang->load("{$comment->module}/{$comment->module}");

                    $modules[$comment->module] = true;
                }
                // If module doesn't exist (for whatever reason) then sssh!
                else {
                    $modules[$comment->module] = false;
                }
            }

            $comment->singular = lang($comment->entry_key) ? lang($comment->entry_key) : humanize($comment->entry_key);
            $comment->plural   = lang($comment->entry_plural) ? lang($comment->entry_plural) : humanize($comment->entry_plural);

            // work out who did the commenting
            if ($comment->user_id > 0) {
                $comment->user_name = anchor('admin/users/edit/' . $comment->user_id, $comment->user_name);
            }

            // Security: Escape any Lex tags
            foreach ($comment as $field => $value) {
                $comment->{$field} = escape_tags($value);
            }
        }

        return $comments;
    }

    /**
     * Load View
     *
     * @return	string	HTML of the comments and form
     */
    protected function load_view($view, $data)
    {
        $ext = pathinfo($view, PATHINFO_EXTENSION) ? '' : '.php';

        if (file_exists(ci()->template->get_views_path() . 'modules/comments/' . $view . $ext)) {
            // look in the theme for overloaded views
            $path = ci()->template->get_views_path() . 'modules/comments/';
        } else {
            // or look in the module
            list($path, $view) = Modules::find($view, 'comments', 'views/');
        }

        // add this view location to the array
        ci()->load->set_view_path($path);
        ci()->load->vars($data);

        return ci()->load->_ci_load(array('_ci_view' => $view, '_ci_return' => true));
    }

    /**
     * Encode Entry
     *
     * @return	string	Return a hash of entry details, so we can send it via a form safely.
     */
    protected function encode_entry()
    {
        return ci()->encrypt->encode(serialize(array(
                    'id'       => $this->entry_id,
                    'title'    => $this->entry_title,
                    'uri'      => $this->entry_uri,
                    'singular' => $this->singular,
                    'plural'   => $this->plural,
        )));
    }

    public function get_post_count($user_id = null)
    {
        $user_id = $user_id != '' ? $user_id : ci()->current_user->id;
        return ci()->comment_m->count_by(array('user_id' => $user_id, 'parent_id' => 0));
    }

    public function get_posts($user_id = null)
    {
        $user_id = $user_id != '' ? $user_id : ci()->current_user->id;
        return ci()->comment_m->get_many_by(array('user_id' => $user_id, 'parent_id' => 0));
    }

    /**
     * To check if the user is allowed to post on an event
     * @param int $event_id
     * @param int $user_id
     */
    public function is_comment_allowed($event_id, $user_id, $post_id)
    {
        return !$this->is_user_blocked($event_id, $user_id) and $this->is_user_following_event($event_id, $user_id);
    }

    /**
     * To check if user is blocked by admin of an event
     * @param int $event_id
     * @param int $user_id
     */
    public function is_user_blocked($event_id, $user_id)
    {
        $event = ci()->eventsmanager_m->get_by('id', $event_id);
        return (bool) ci()->comment_blacklists_m->count_by(array('author_id' => $event->author, 'blocked_user_id' => $user_id));
    }

    /**
     * To check if user is following an event
     * @param int $event_id
     * @param int $user_id
     */
    public function is_user_following_event($event_id, $user_id)
    {
        return (bool) ci()->trends->count_by(array('entry_id' => $event_id, 'user_id' => $user_id, 'follow' => 'true'));
    }

    /**
     * To check if post is created by one of user's friend post
     * @param int $friend_id
     * @param int $post_id
     * @param int|null $user_id
     */
    public function is_friends_post($post_id, $user_id)
    {
        $post = ci()->comment_m->get_by('id', $post_id);
        ci()->load->library('friend/friend');
        $friendship = ci()->friend->is_friend($post->user_id, $user_id);
        return $friendship->status == 'accepted' ? true : false; 
    }

}
