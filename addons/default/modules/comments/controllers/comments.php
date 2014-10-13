<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Comments controller (frontend)
 *
 * @package		PyroCMS\Core\Modules\Comments\Controllers
 * @author		PyroCMS Dev Team
 * @copyright   Copyright (c) 2012, PyroCMS LLC
 */
class Comments extends Public_Controller
{

    /**
     * An array containing the validation rules
     * 
     * @var array
     */
    private $validation_rules = array(
        array(
            'field' => 'name',
            'label' => 'lang:comments:name_label',
            'rules' => 'trim'
        ),
        array(
            'field' => 'email',
            'label' => 'lang:global:email',
            'rules' => 'trim|valid_email'
        ),
        array(
            'field' => 'website',
            'label' => 'lang:comments:website_label',
            'rules' => 'trim|max_length[255]'
        ),
        array(
            'field' => 'comment',
            'label' => 'lang:comments:message_label',
            'rules' => 'trim|required'
        ),
    );

    /**
     * Constructor method
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        // Load the required classes
        $this->load->library('form_validation');
        $this->load->library('trends/trends');
        $this->load->model('comment_m');
        $this->lang->load('comments');
    }

    /**
     * Create a new comment
     *
     * @param type $module The module that has a comment-able model.
     * @param int $id The id for the respective comment-able model of a module.
     */
    public function create($module = null)
    {
        if (!$module or ! $this->input->post('entry')) {
            show_404();
        }
        $media_content         = $this->input->post('media_content');
        $decoded_media_content = $this->encrypt->decode($this->input->post('media_content'));
        $media                 = unserialize($decoded_media_content);
        // Get information back from the entry hash
        // @HACK This should be part of the controllers lib, but controllers & libs cannot share a name
        $entry                 = unserialize($this->encrypt->decode($this->input->post('entry')));
        
        $event = $this->eventsmanager_m->get_by('id', $entry['id']);
        $comment = array(
            'parent_id'    => $this->input->post('parent_id'),
            'module'       => $module,
            'entry_id'     => $entry['id'],
            'entry_title'  => $entry['title'],
            'entry_key'    => $entry['singular'],
            'entry_plural' => $entry['plural'],
            'uri'          => $entry['uri'],
            'comment'      => $this->input->post('comment'),
            'media'        => !empty($media_content) ? $media_content : '',
        );
        
        $comment['is_active'] = 0;
        $auto_approved = true; 
        if((!empty($event) && is_object($event))){
        $this->load->model('profile/auto_approval_m'); 
        $auto_approved = (bool)$this->auto_approval_m->count_by(
                array(
                        'admin_id' => $event->author,
                        'user_id' => $this->current_user->id,
                        'approval_type' => 'comment',
                        'status' => 'on'
                )
            );
        }
        $this->db->set_dbprefix('default_');
        if($auto_approved 
                or(isset($this->current_user->group) and $this->current_user->group == 'admin') 
                or $event->comment_approval == 'NO'
                or ($this->current_user->id == $event->author)){
            $comment['is_active'] = 1;
        }
       
        // Logged in? in which case, we already know their name and email
        if ($this->current_user) {
            $comment['user_id']      = $this->current_user->id;
            $comment['user_name']    = $this->current_user->display_name;
            $comment['user_email']   = $this->current_user->email;
            $comment['user_website'] = $this->current_user->website;

            if (isset($this->current_user->website)) {
                $comment['website'] = $this->current_user->website;
            }
        } else {
            $this->validation_rules[0]['rules'] .= '|required';
            $this->validation_rules[1]['rules'] .= '|required';

            $comment['user_name']    = $this->input->post('name');
            $comment['user_email']   = $this->input->post('email');
            $comment['user_website'] = $this->input->post('website');
        }

        // Set the validation rules
        $this->form_validation->set_rules($this->validation_rules);
        $comment_id = null;
        // Validate the results
        if ($this->form_validation->run()) {
            // ALLOW ZEH COMMENTS!? >:D
            $result = $this->_allow_comment();

            foreach ($comment as &$data) {
                // Remove {pyro} tags and html
                $data = escape_tags($data);
            }

            // Run Akismet or the crazy CSS bot checker
            if ($result['status'] !== true) {
                $this->session->set_flashdata('comment', $comment);
                $this->session->set_flashdata('error', $result['message']);

                $this->_repopulate_comment();
            } else {
                // Save the comment
                if ($comment_id = $this->comment_m->insert($comment)) {
                    if (!$auto_approved and $event->comment_approval == 'YES') {
                        Notify::trigger(Notify::TYPE_COMMENT, array(
                            'rec_id' => $event->author,
                            'data'   => array(
                                'comment_id' => $comment_id,
                                'media'   => $comment['media'],
                                'comment' => $comment['comment']
                            )
                        ));
                    }
                    if ($this->input->is_ajax_request()) {
                        $parent_id              = $this->input->post('parent_id') ? $this->input->post('parent_id') : 0;
                        $comment = $this->db->select('*, if(c.user_id = e.author, 1, 0) as is_author_post', false)
                                ->from('comments as c')
                                ->join('events as e','e.id = c.entry_id', 'left')
                                ->where('c.id', $comment_id)
                                ->group_by('c.id')
                                ->get()
                                ->row();
                        $response['parent_id']  = $parent_id;
                        $response['comment_id'] = $comment_id;
                        $response['entry']      = $this->input->post('entry');
                        if($comment->is_author_post) {
                            if (is_file(UPLOAD_PATH . 'files/' . $comment->thumbnail)) :
                                $response['pic_creator']        = img(array('src' => UPLOAD_PATH . 'files/' . $comment->thumbnail, 'height' => 50, 'width' => 50));
                                $response['pic']        = img(array('src' => UPLOAD_PATH . 'files/' . $comment->thumbnail, 'height' => 32, 'width' => 32));
                            elseif (isset($event->picture_id)) :
                                $response['pic_creator']        = img(array('src' => 'files/thumb/' . $comment->picture_id . '/50'));
                                $response['pic']        = img(array('src' => 'files/thumb/' . $comment->picture_id . '/32'));
                            else :
                                $response['pic_creator']        =  img(array('src' => '/addons/default/modules/eventsmanager/img/event.png', 'width'=>50, 'height'=>50));
                                $response['pic']        =  img(array('src' => '/addons/default/modules/eventsmanager/img/event.png', 'width'=>32, 'height'=>32));
                            endif;
                        }else{
                            $this->load->model('users/profile_m');
                            $response['pic_creator']        = $this->profile_m->get_profile_pic($comment->user_id, 32);
                            $response['pic']        = $this->profile_m->get_profile_pic($comment->user_id, 32);   
                        }                    
                        $response['user_email'] = $comment->user_email;

                        $response['user_name']  = ($comment->is_author_post) ? $comment->entry_title : $this->current_user->display_name; 
                        $response['media']      = '';
                        $response['time_ago']   = time_passed(strtotime($comment->created_on));
                        if(!isset($comment->display_name)){
                            $comment->display_name = $this->current_user->display_name; 
                        }
                        if (!empty($media['data'])) {
                            $response['media']['baseurl']  = base_url();
                            $response['media']['id']       = $media['data']['id'];
                            $response['media']['filename'] = $media['data']['filename'];
                        }

                        if (Settings::get('comment_markdown') and $comment->parsed) {
                            $response['comment'] = $comment->parsed;
                        } else {
                            $response['comment'] = nl2br($comment->comment);
                        }

                        $this->load->library('trends/trends');

                        $response['link_star'] = $this->trends->link_star($comment_id, 'comment');
                        echo json_encode($response);
                        exit;
                    }
                    // Approve the comment straight away
                    if (!$this->settings->moderate_comments or ( isset($this->current_user->group) and $this->current_user->group == 'admin')) {
                        $this->session->set_flashdata('success', lang('comments:add_success'));

                        // Add an event so third-party devs can hook on
                        Events::trigger('comment_approved', $comment);
                    }

                    // Do we need to approve the comment?
                    else {
                        $this->session->set_flashdata('success', lang('comments:add_approve'));
                    }

                    $comment['comment_id'] = $comment_id;

                    // If markdown is allowed we will parse the body for the email
                    if (Settings::get('comment_markdown')) {
                        $comment['comment'] = parse_markdown($comment['comment']);
                    }

                    // Send the notification email
                    //$this->_send_email($comment, $entry);
                }

                // Failed to add the comment
                else {
                    $this->session->set_flashdata('error', lang('comments:add_error'));

                    $this->_repopulate_comment();
                }
            }
        }

        // The validation has failed
        else {
            $this->session->set_flashdata('error', validation_errors());

            $this->_repopulate_comment();
        }


        // If for some reason the post variable doesnt exist, just send to module main page
        $uri = !empty($entry['uri']) ? $entry['uri'] : $module;

        // If this is default to pages then just send it home instead
        $uri === 'pages' and $uri = '/';

        redirect($uri);
    }

    public function create_media($module = null)
    {
        if (!$module or ! $this->input->post('entry')) {
            show_404();
        }

        // Get information back from the entry hash
        // @HACK This should be part of the controllers lib, but controllers & libs cannot share a name
        $entry = unserialize($this->encrypt->decode($this->input->post('entry')));



        $comment = array(
            'parent_id'    => $this->input->post('parent_id'),
            'module'       => $module,
            'entry_id'     => $entry['id'],
            'entry_title'  => $entry['title'],
            'entry_key'    => $entry['singular'],
            'entry_plural' => $entry['plural'],
            'uri'          => $entry['uri'],
            'comment'      => $this->input->post('comment'),
            'is_active'    => (bool) ((isset($this->current_user->group) and $this->current_user->group == 'admin') or ! Settings::get('moderate_comments')),
        );

        // Logged in? in which case, we already know their name and email
        if ($this->current_user) {
            $comment['user_id']      = $this->current_user->id;
            $comment['user_name']    = $this->current_user->display_name;
            $comment['user_email']   = $this->current_user->email;
            $comment['user_website'] = $this->current_user->website;

            if (isset($this->current_user->website)) {
                $comment['website'] = $this->current_user->website;
            }
        } else {
            $this->validation_rules[0]['rules'] .= '|required';
            $this->validation_rules[1]['rules'] .= '|required';

            $comment['user_name']    = $this->input->post('name');
            $comment['user_email']   = $this->input->post('email');
            $comment['user_website'] = $this->input->post('website');
        }

        // Set the validation rules
        $this->form_validation->set_rules($this->validation_rules);

        // Validate the results
        if ($this->form_validation->run()) {
            // ALLOW ZEH COMMENTS!? >:D
            $result = $this->_allow_comment();

            foreach ($comment as &$data) {
                // Remove {pyro} tags and html
                $data = escape_tags($data);
            }

            // Run Akismet or the crazy CSS bot checker
            if ($result['status'] !== true) {
                $this->session->set_flashdata('comment', $comment);
                $this->session->set_flashdata('error', $result['message']);

                $this->_repopulate_comment();
            } else {
                // Save the comment
                if ($comment_id = $this->comment_m->insert($comment)) {
                    // Approve the comment straight away
                    if (!$this->settings->moderate_comments or ( isset($this->current_user->group) and $this->current_user->group == 'admin')) {
                        $this->session->set_flashdata('success', lang('comments:add_success'));

                        // Add an event so third-party devs can hook on
                        Events::trigger('comment_approved', $comment);
                    }

                    // Do we need to approve the comment?
                    else {
                        $this->session->set_flashdata('success', lang('comments:add_approve'));
                    }

                    $comment['comment_id'] = $comment_id;

                    // If markdown is allowed we will parse the body for the email
                    if (Settings::get('comment_markdown')) {
                        $comment['comment'] = parse_markdown($comment['comment']);
                    }

                    // Send the notification email
                    //$this->_send_email($comment, $entry);
                }

                // Failed to add the comment
                else {
                    $this->session->set_flashdata('error', lang('comments:add_error'));

                    $this->_repopulate_comment();
                }
            }
        }

        // The validation has failed
        else {
            $this->session->set_flashdata('error', validation_errors());

            $this->_repopulate_comment();
        }


        // If for some reason the post variable doesnt exist, just send to module main page
        $uri = !empty($entry['uri']) ? $entry['uri'] : $module;

        // If this is default to pages then just send it home instead
        $uri === 'pages' and $uri = '/';
        if ($this->input->is_ajax_request()) {
            $parent_id              = $this->input->post('parent_id') ? $this->input->post('parent_id') : 0;
            $comment                = current($this->comment_m->get_by_entry($comment['module'], $comment['entry_key'], $comment['entry_id'], true, $parent_id, $comment_id));
            $response['parent_id']  = $parent_id;
            $response['comment_id'] = $comment_id;
            $response['entry']      = $this->input->post('entry');
            $response['pic']        = gravatar($comment->user_email, 60);
            $response['user_email'] = $comment->user_email;
            $response['user_name']  = $comment->user_name;
            if (Settings::get('comment_markdown') and $comment->parsed) {
                $response['comment'] = $comment->parsed;
            } else {
                $response['comment'] = nl2br($comment->comment);
            }
            echo json_encode($response);
        } else {

            redirect($uri);
        }
    }

    /**
     * Repopulate Comment
     *
     * There are a few places where we need to repopulate
     * the comments.
     *
     * @access 	private
     * @return 	void
     */
    private function _repopulate_comment()
    {
        // Loop through each rule
        foreach ($this->validation_rules as $rule) {
            if ($this->input->post($rule['field']) !== false) {
                $comment[$rule['field']] = escape_tags($this->input->post($rule['field']));
            }
        }
        $this->session->set_flashdata('comment', $comment);
    }

    /**
     * Method to check whether we want to allow the comment or not
     * 
     * @return array
     */
    private function _allow_comment()
    {
        // Dumb-check
        $this->load->library('user_agent');
        $this->load->model('comment_blacklists_m');

        // Sneaky bot-check
        if ($this->agent->is_robot() or $this->input->post('d0ntf1llth1s1n')) {
            return array('status' => false, 'message' => 'You are probably a robot.');
        }

        // Check Akismet if an API key exists
        if (Settings::get('akismet_api_key')) {
            $this->load->library('akismet');

            $comment = array(
                'author'  => $this->current_user ? $this->current_user->display_name : $this->input->post('name'),
                'email'   => $this->current_user ? $this->current_user->email : $this->input->post('email'),
                'website' => (isset($this->current_user->website)) ? $this->current_user->website : $this->input->post('website'),
                'body'    => $this->input->post('body')
            );

            $config = array(
                'blog_url' => BASE_URL,
                'api_key'  => Settings::get('akismet_api_key'),
                'comment'  => $comment
            );

            $this->akismet->init($config);

            if ($this->akismet->is_spam()) {
                return array('status' => false, 'message' => 'Looks like this is spam. If you believe this is incorrect please contact the site administrator.');
            }

            if ($this->akismet->errors_exist()) {
                return array('status' => false, 'message' => implode('<br />', $this->akismet->get_errors()));
            }
        }

//        // Do our own blacklist check.
//        $blacklist = array(
//            'email'   => $this->input->post('email'),
//            'website' => $this->input->post('website')
//        );
//
//        if ($this->comment_blacklists_m->is_blacklisted($blacklist)) {
//            return array('status' => false, 'message' => 'The website or email address posting this comment has been blacklisted.');
//        }

        // F**k knows, its probably fine...
        return array('status' => true);
    }

    /**
     * Send an email
     *
     * @param array $comment The comment data.
     * @param array $entry The entry data.
     * @return boolean 
     */
    private function _send_email($comment, $entry)
    {
        $this->load->library('email');
        $this->load->library('user_agent');

        // Add in some extra details
        $comment['slug']         = 'comments';
        $comment['sender_agent'] = $this->agent->browser() . ' ' . $this->agent->version();
        $comment['sender_ip']    = $this->input->ip_address();
        $comment['sender_os']    = $this->agent->platform();
        $comment['redirect_url'] = anchor(ltrim($entry['uri'], '/') . '#' . $comment['comment_id']);
        $comment['reply-to']     = $comment['user_email'];

        //trigger the event
        return (bool) Events::trigger('email', $comment);
    }

    public function share($entry_id = null)
    {
        if ($post = $this->input->post()) {
            $this->load->model('share_m');
            $post     = $post + array('user_id' => $this->current_user->id);
            if ($inserted = $this->share_m->insert($post)) {
                $this->load->model('friend/friend_m'); 
                $friends = $this->friend_m->get_friends($this->current_user->id);
                foreach($friends as $friend){
                    $notification = array('rec_id' => $friend->user_id, 'data' => $post);
                    Notify::trigger(Notify::TYPE_SHARE, $notification); 
                }
                echo json_encode(array('status' => 'success'));
            } else {
                echo json_encode(array('status' => 'failure'));
            }
            exit;
        }

        $entry = null;
        if ($entry_id != '') {
            $entry = $this->comment_m->get_by(array('id' => $entry_id));
        }
        $this->template
                ->set_layout(false)
                ->set('post', $entry)
                ->build('share');
    }

    /**
     * Approve a comment
     * 
     * @param  mixed $ids		id or array of ids to process
     * @return void
     */
    public function approve()
    {
        $this->_do_action($this->input->get('cid'), $this->input->get('action'));
    }
    
    /**
    * Do the actual work for approve/unapprove
    * @access protected
    * @param  int|array $ids	id or array of ids to process
    * @param  string $action	action to take: maps to model
    * @return void
    */
   protected function _do_action($ids, $action)
   {
           $ids		= ( ! is_array($ids)) ? array($ids) : $ids;
           $multiple	= (count($ids) > 1) ? '_multiple' : null;
           $status		= 'success';

           foreach ($ids as $id)
           {
                   if ( ! $this->comment_m->{$action}($id))
                   {
                           $status = 'error';
                           break;
                   }
                   if ($action == 'approve')
                   {
                           // add an event so third-party devs can hook on
                           Events::trigger('comment_approved', $this->comment_m->get($id));
                   }
                   else
                   {
                           Events::trigger('comment_unapproved', $id);
                   }
           }
           
           $this->template
                   ->build_json(array($status => lang('comments:' . $action . '_' . $status . $multiple)));
   }
   
   public function block()
   {
       $this->load->model('comment_blacklists_m');
       
       $uid = $this->input->get('uid'); 
       if($uid!=''){
           $data = array('author_id' => $this->current_user->id,
               'blocked_user_id' => $uid,
               'status' => 'block');
           if($this->comment_blacklists_m->save($data)){
               $this->template
                       ->build_json(array('success' => 'This user is blocked now.')); 
           }
       }
   }
   
   public function delete()
   {
       if ($this->input->is_ajax_request()) {
           $comment_id = $this->input->post('id');
           $d = $this->comment_m->get_comment_details($comment_id);
            if (($d->post_author_id == $this->current_user->id or $d->event_author_id == $this->current_user->id)) {
                if ($this->comment_m->soft_delete($comment_id)) {
                    $this->template
                            ->build_json(array('status' => 'success', 'msg' => 'Post deleted successfully'));
                }
            } else{
                $this->template
                            ->build_json(array('status' => 'failure', 'msg' => 'You are not authorized to delete the post.'));
            }
        } else {
            redirect('/densealife-page');
        }
   }
   
   public function view_more()
   {
       $parent = $this->input->post('post_id'); 
       $limit = 10;
       $offset = $this->input->post('offset');
       $offset = empty($offset) ? 5 : $offset ;
       
       $this->template
               ->set_layout(false);
       $html = $this->template
               ->set_layout(false)
               ->set('comments', $this->comment_m->get_by_parent($parent, $limit, $offset))
               ->build('display_children');
       $remaining = $this->comment_m->from('comments')->limit($limit, $offset)->count_by(array('parent_id' => $parent, 'is_active' => 1));
       
       $this->template
               ->build_json(array('html' => $html, 'offset' => (($offset+1) + $limit), 'remaining' => $remaining ));
   }
}
