<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Friend module
 *
 * @author  PyroCMS Dev Team
 * @package PyroCMS\Core\Modules\Friend
 */
class Module_Friend extends Module
{

	public $version = '1.1.0';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Friend',
				
			),
			'description' => array(
				'en' => 'Users and guests can write comments for content like blog, pages and photos.',
			),
			'frontend' => true,
			'backend' => true,
			'menu' => 'Event Management'
		);
	}

	public function install()
	{
		return true;
	}

	public function uninstall()
	{
		// This is a core module, lets keep it around.
		return false;
	}

	public function upgrade($old_version)
	{
		return true;
	}

}
