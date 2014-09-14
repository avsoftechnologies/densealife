<?php
defined('BASEPATH') OR exit('No direct script access allowed') ;

/**
 * Comments controller (frontend)
 *
 * @package		PyroCMS\Core\Modules\Comments\Controllers
 * @author		PyroCMS Dev Team
 * @copyright   Copyright (c) 2012, PyroCMS LLC
 */
class Trends extends Public_Controller
{

    /**
     * Constructor method
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct() ;
        $this->load->model('trend_m') ;
        $this->lang->load('trends') ;
        $this->load->helper('functions');
        if ( !is_logged_in() ) {
            $this->session->set_userdata('redirect_to', current_url()) ;
            redirect('users/login') ;
        }
    }    
    /**
     * Create a new trend
     *
     * @param type $module The module that has a trend-able model.
     * @param int $id The id for the respective trend-able model of a module.
     */
    public function create()
    {
        
        $post = $this->input->post();
        if($post){
            $decrypt = unserialize($this->encrypt->decode($post['data']));
            $reload = empty($decrypt['reload']) ? 'false' : 'true';
            // Save the trend
            if ( $trendIncrement = $this->trend_m->insert($decrypt) ) {
                echo json_encode(array( 'message' => 'success',
                    'action' => $trendIncrement,
                    'entry' => $decrypt['entry_id'],
                    'trend' => $decrypt['trend'],
                    'reload' => $reload));
                exit;
            } else {
                echo json_encode(array( 'message' => 'failure' ));
                exit;
            }
        }else{
            redirect('/densealife-page');
        }
    }

    public function update($module = null)
    {
        if ( !$module or !$this->input->post('entry') ) {
            show_404() ;
        }
        if(!is_logged_in()){
            redirect('');
        }

        // Get information back from the entry hash
        // @HACK This should be part of the controllers lib, but controllers & libs cannot share a name
        $entry = unserialize($this->encrypt->decode($this->input->post('entry'))) ;
        $trend = array(
                'module'       => $module,
                'entry_id'     => $entry['id'],
                'trend'        => $this->input->post('trend'),
            ) ;
        
        $this->trend_m->select('id')
                ->from('trends')
                ->where($trend)
                ->get_all();

        
        if ( $this->current_user ) {
                $trend['user_id']      = $this->current_user->id ;
        }
        // Save the trend
        if ( $trend_id = $this->trend_m->insert($trend) ) {

            $this->session->set_flashdata('success', lang('trends:add_success')) ;

            // Add an event so third-party devs can hook on
            Events::trigger('trend_approved', $trend) ;

            $trend['trend_id'] = $trend_id ;
            
        }

        // Failed to add the trend
        else {
            $this->session->set_flashdata('error', lang('trends:add_error')) ;
        }


        // If for some reason the post variable doesnt exist, just send to module main page
        $uri = !empty($entry['uri']) ? $entry['uri'] : $module ;

        // If this is default to pages then just send it home instead
        $uri === 'pages' and $uri = '/' ;
        if ( $this->input->is_ajax_request() ) {
            $parent_id              = $this->input->post('parent_id') ? $this->input->post('parent_id') : 0 ;
            $trend                  = current($this->trend_m->get_by_entry($trend['module'], $trend['entry_key'], $trend['entry_id'], true, $parent_id, $trend_id)) ;
            $response['parent_id']  = $parent_id ;
            $response['trend_id']   = $trend_id ;
            $response['entry']      = $this->input->post('entry') ;
            $response['pic']        = gravatar($trend->user_email, 60) ;
            $response['user_email'] = $trend->user_email ;
            $response['user_name']  = $trend->user_name ;
            if ( Settings::get('trend_markdown') and $trend->parsed ) {
                $response['trend'] = $trend->parsed ;
            } else {
                $response['trend'] = nl2br($trend->trend) ;
            }
            echo json_encode($response) ;
        } else {

            redirect($uri) ;
        }
    }
    /**
     * Send an email
     *
     * @param array $trend The trend data.
     * @param array $entry The entry data.
     * @return boolean 
     */
    private function _send_email($trend, $entry)
    {
        $this->load->library('email') ;
        $this->load->library('user_agent') ;

        // Add in some extra details
        $trend['slug']         = 'trends' ;
        $trend['sender_agent'] = $this->agent->browser() . ' ' . $this->agent->version() ;
        $trend['sender_ip']    = $this->input->ip_address() ;
        $trend['sender_os']    = $this->agent->platform() ;
        $trend['redirect_url'] = anchor(ltrim($entry['uri'], '/') . '#' . $trend['trend_id']) ;
        $trend['reply-to']     = $trend['user_email'] ;

        //trigger the event
        return ( bool ) Events::trigger('email', $trend) ;
    }

}
