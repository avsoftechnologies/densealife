<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package 		PyroCMS
 * @subpackage 		Upcoming events widget
 * @author              Ankit Vishwakarma <ankitvishwakarma@sify.com>
 *
 * Show the upcoming events with a widget that can be integrated everywhere.
 *
 */

class Widget_FB_Login extends Widgets
{
	public $title       =   array(
                                    'en' => 'Facebook Login Button',
                                );
	public $description =   array(
                                    'en' => 'Facebook Login Button',
                                );
	public $author		= 'Ankit Vishwakarma <ankitvishwakarma@sify.com>';
	public $website		= 'http://www.avsoftechnologies.in';
	public $version		= '1.1';

	// build form fields for the backend
	// MUST match the field name declared in the form.php file
	public $fields = array(
		array(
			'field' => 'limit',
			'label' => 'People you may know',
		)
	);

	public function form($options)
	{
		!empty($options['limit']) OR $options['limit'] = 5;
		
		return array(
			'options' => $options
		);
	}

	public function run($options)
	{
           // return true;
                if(!is_logged_in()) return false; 
		// Load the language file
		$this->load->language('users/user');
		
		// load the eventsmanager module's model
		class_exists('friend_m') OR $this->load->model('friend/friend_m');
                
		// sets default number of events to be shown
		empty($options['limit']) AND $options['limit'] = 5;

		// retrieve the records using the blog module's model
		$friend_suggestions = $this->friend_m->get_friend_suggestions($options['limit']);
                
                foreach($friend_suggestions as &$suggestion){
                    
                    if(is_null($suggestion->status_label) && is_null($suggestion->status)){
                        $suggestion->status_label = 'Invite Friend';
                    }elseif($suggestion->status=='accepted'){
                        $suggestion->status_label = 'Friends';
                    }
                    $suggestion->count_mutual_friends = count($this->friend_m->get_mutual_friends($suggestion->user_id));

                }
                
		return array('suggestions' => $friend_suggestions);
	}
}