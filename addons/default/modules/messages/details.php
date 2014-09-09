<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Messages extends Module
{
	public $version = '1.0.0';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Message'
			),
			'description' => array(
				'en' => 'Message',
			),
			'frontend' => true,
			'backend'  => true,
//			'menu'	  => '',
		);
	}

	public function install()
	{
            $this->dbforge->drop_table('messages') ;
             // Create the blog categories table.
            $this->install_tables(array(
                'messages' => array(
                    'id'    => array( 'type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true ),
                    'sender_id'  => array( 'type' => 'INT', 'constraint' => 11, 'null' => false, 'key' => true ),
                    'rec_id' => array( 'type' => 'INT', 'constraint' => 11, 'null' => false, 'key' => true ),
                    'message' => array( 'type' =>'TEXT', 'null' => false),
                ),
            ));

            return true; 
	}

	public function uninstall()
	{
            $this->dbforge->drop_table('messages', true) ;
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