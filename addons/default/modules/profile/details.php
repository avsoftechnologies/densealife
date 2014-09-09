<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Profile extends Module
{
	public $version = '1.0.3';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Profile'
			),
			'description' => array(
				'en' => 'This module extending the core user module',
			),
			'frontend' => true,
			'backend'  => true,
			'menu'	  => '',
		);
	}

	public function install()
	{
		return true; 
	}

	public function uninstall()
	{
		
		return true;
	}

	public function upgrade($old_version)
	{
		// Your Upgrade Logic
		return TRUE;
	}

	public function help()
	{
		// Return a string containing help info
		// You could include a file and return it here.
		return "No documentation has been added for this module.";
	}
}
/* End of file details.php */