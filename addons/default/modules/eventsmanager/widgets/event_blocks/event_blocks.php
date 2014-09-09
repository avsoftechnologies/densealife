<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package 		PyroCMS
 * @subpackage 		Upcoming events widget
 * @author			Ankit Vishwakarma <ankitvishwakarma@sify.com>
 *
 * Show the upcoming events with a widget that can be integrated everywhere.
 *
 */

class Widget_Event_Blocks extends Widgets
{
	public $title		= array(
		'en' => 'Upcoming events',
	);
	public $description	= array(
		'en' => 'Display upcoming events',
	);
	public $author		= 'Ankit Vishwakarma <ankitvishwakarma@sify.com>';
	public $website		= 'http://www.avsoftechnologies.in';
	public $version		= '1.1';

	// build form fields for the backend
	// MUST match the field name declared in the form.php file
	public $fields = array(
		array(
			'field' => 'limit',
			'label' => 'Number of events',
		)
	);

	public function form($options)
	{
		// Load the language file
		$this->load->language('eventsmanager/widget_nextevents');
		
		!empty($options['limit']) OR $options['limit'] = 5;
		
		return array(
			'options' => $options
		);
	}

	public function run($options)
	{
		// Load the language file
		$this->load->language('eventsmanager/widget_nextevents');
		
		// load the eventsmanager module's model
		class_exists('Eventsmanager_m') OR $this->load->model('eventsmanager/eventsmanager_m');
		
		// load the settings library
		class_exists('Settings') OR $this->load->library('settings');

		// sets default number of events to be shown
		empty($options['limit']) AND $options['limit'] = 5;

		// retrieve the records using the blog module's model
		$agenda_widget = $this->eventsmanager_m->get_next($options['limit']);
		
		// returns the variables to be used within the widget's view
		return array('agenda_widget' => $agenda_widget);
	}
}