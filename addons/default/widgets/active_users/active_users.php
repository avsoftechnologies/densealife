<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package 		PyroCMS
 * @subpackage 		Upcoming events widget
 * @author              Ankit Vishwakarma <ankitvishwakarma@sify.com>
 *
 * Show the upcoming events with a widget that can be integrated everywhere.
 *
 */

class Widget_Active_Users extends Widgets
{
	public $title       =   array(
                                    'en' => 'Most Active Users',
                                );
	public $description =   array(
                                    'en' => 'Display users with most follows,favourite &amp; stars',
                                );
	public $author		= 'Ankit Vishwakarma <ankitvishwakarma@sify.com>';
	public $website		= 'http://www.avsoftechnologies.in';
	public $version		= '1.1';

	// build form fields for the backend
	// MUST match the field name declared in the form.php file
	public $fields = array(
		array(
			'field' => 'limit',
			'label' => 'Number of Users',
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
		// Load the language file
		$this->load->language('users/user');
		
		// load the eventsmanager module's model
		class_exists('profile_m') OR $this->load->model('users/profile_m');
		
		// load the settings library
		class_exists('Settings') OR $this->load->library('settings');

		// sets default number of events to be shown
		empty($options['limit']) AND $options['limit'] = 5;

		// retrieve the records using the blog module's model
		$active_users = $this->profile_m->get_most_active_users($options['limit']);
		
		// returns the variables to be used within the widget's view
		return array('active_users' => $active_users);
	}
}