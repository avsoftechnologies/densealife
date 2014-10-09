<?php

defined('BASEPATH') or exit('No direct script access allowed') ;

class Module_EventsManager extends Module
{

    public $version = '2.0.1' ;

    public function info()
    {
        return array(
            'name'        => array(
                'en' => 'Events Manager',
            ),
            'description' => array(
                'en' => 'Manage your events in details then show them to your visitors.',
            ),
            'frontend'    => true,
            'backend'     => true,
            'menu'        => 'structure',
            'roles'       => array(
                'frontend_editing'
            ),
            'sections'    => array(
                'events_list' => array(
                    'name'      => 'eventsmanager:list_label',
                    'uri'       => 'admin/eventsmanager',
                    'shortcuts' => array(
                        'create' => array(
                            'name'  => 'eventsmanager:new_event_label',
                            'uri'   => 'admin/eventsmanager/create',
                            'class' => 'add'
                        )
                    )
                ),
                'pending_events_list' => array(
                    'name'      => 'Pending Event List',
                    'uri'       => 'admin/eventsmanager/approve',
                ),
                'categories'  => array(
                    'name'      => 'cat:list_title',
                    'uri'       => 'admin/eventsmanager/categories',
                    'shortcuts' => array(
                        array(
                            'name'  => 'cat:create_title',
                            'uri'   => 'admin/eventsmanager/categories/create',
                            'class' => 'add',
                        ),
                    ),
                ),
            )
                ) ;
    }

    private $custom_settings = array(
        'events_date_interval' => array(
            'slug'        => 'events_date_interval',
            'title'       => 'Default end date interval',
            'description' => 'The default interval between the start date and the end date',
            'type'        => 'text',
            'default'     => 1,
            'value'       => 1,
            'options'     => '',
            'is_required' => true,
            'is_gui'      => true,
            'module'      => 'eventsmanager',
            'order'       => 10 ),
        'events_map_width'     => array(
            'slug'        => 'events_map_width',
            'title'       => 'Map canvas width',
            'description' => 'The width of the map canvas in events details. (px,%)',
            'type'        => 'text',
            'default'     => '100%',
            'value'       => '100%',
            'options'     => '',
            'is_required' => true,
            'is_gui'      => true,
            'module'      => 'eventsmanager',
            'order'       => 9 ),
        'events_map_height'    => array(
            'slug'        => 'events_map_height',
            'title'       => 'Map canvas height',
            'description' => 'The height of the map canvas in events details. (px,%)',
            'type'        => 'text',
            'default'     => '200px',
            'value'       => '200px',
            'options'     => '',
            'is_required' => true,
            'is_gui'      => true,
            'module'      => 'eventsmanager',
            'order'       => 8 ),
            ) ;

    public function install()
    {
        $this->dbforge->drop_table('event_categories') ;

        $this->load->driver('Streams') ;
        $this->streams->utilities->remove_namespace('events') ;

        // Just in case.
        $this->dbforge->drop_table('events') ;

        if ( $this->db->table_exists('data_streams') ) {
            $this->db->where('stream_namespace', 'events')->delete('data_streams') ;
        }

        // Create the blog categories table.
        $this->install_tables(array(
            'event_categories' => array(
                'id'    => array( 'type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true ),
                'slug'  => array( 'type' => 'VARCHAR', 'constraint' => 100, 'null' => false, 'unique' => true, 'key' => true ),
                'title' => array( 'type' => 'VARCHAR', 'constraint' => 100, 'null' => false, 'unique' => true ),
            ),
        )) ;

        $this->streams->streams->add_stream(
                'lang:blog:blog_title', 'blog', 'blogs', null, null
        ) ;

        // Add the intro field.
        // This can be later removed by an admin.
        $intro_field = array(
            'name'      => 'lang:blog:intro_label',
            'slug'      => 'intro',
            'namespace' => 'blogs',
            'type'      => 'wysiwyg',
            'assign'    => 'blog',
            'extra'     => array( 'editor_type' => 'simple', 'allow_tags' => 'y' ),
            'required'  => true
                ) ;
        $this->streams->fields->add_field($intro_field) ;

        $this->db->delete('settings', array( 'module' => 'eventsmanager' )) ;

        // Create events manager table
        $eventsmanager = array(
            'id'               => array(
                'type'           => 'INT',
                'constraint'     => '11',
                'auto_increment' => true
            ),
            'category_id'      => array(
                'type'       => 'INT',
                'constraint' => '11',
            ),
            'title'            => array(
                'type' => 'TEXT'
            ),
            'slug'             => array(
                'type' => 'TEXT'
            ),
            'description'      => array(
                'type' => 'TEXT'
            ),
            'place'            => array(
                'type' => 'TEXT'
            ),
            'start_date'       => array(
                'type' => 'DATETIME'
            ),
            'end_date'         => array(
                'type' => 'DATETIME',
                'null' => true
            ),
            'end_date_defined' => array(
                'type' => 'BOOL',
            ),
            'author'           => array(
                'type'     => 'INT',
                'unsigned' => true
            ),
            'picture_id'       => array(
                'type'       => 'CHAR',
                'constraint' => '15',
                'null'       => true
            ),
            'thumbnail'        => array(
                'type' => 'TEXT',
                'null' => true
            ),
            'show_map'         => array(
                'type'    => 'BOOL',
                'null'    => true,
                'default' => 0 // false
            ),
            'pos_lat'          => array(
                'type' => 'DOUBLE',
                'null' => true
            ),
            'pos_lng'          => array(
                'type' => 'DOUBLE',
                'null' => true
            ),
            'enable_comments'  => array(
                'type'    => 'BOOL',
                'default' => 0 // false
            ),
            'published'        => array(
                'type'    => 'BOOL',
                'default' => 0 // false
            )
                ) ;

        $this->dbforge->add_field($eventsmanager) ;
        $this->dbforge->add_key('id', true) ;

        if ( !$this->dbforge->create_table('events', true) ) { // IF NOT EXISTS
            return false ;
        }
        // No upload path for our module? If we can't make it then fail
        /* if ( ! is_dir($this->upload_path.'eventsmanager') AND ! @mkdir($this->upload_path.'eventsmanager',0777,true))
          {
          return false;
          } */

        $this->load->library('settings') ;
        foreach ( $this->custom_settings as $setting )
            $this->settings->add($setting) ;

        // Yes we did it ! Did iiiiit !
        return true ;
    }

    public function admin_menu(&$menu)
    {
        $menu['Event Management'] = array(
            'Event Listing'           => 'admin/eventsmanager',
            'Create Event'            => 'admin/eventsmanager/create',
            'Category Listing'        => 'admin/eventsmanager/categories',
            'Create Category'         => 'admin/eventsmanager/categories/create',
            'Pending Approvals'       => 'admin/eventsmanager/approve',
        ) ;

        add_admin_menu_place('Event Management', 1) ;
    }

    public function uninstall()
    {
        $this->dbforge->drop_table('events', true) ;
        $this->db->delete('settings', array( 'module' => 'eventsmanager' )) ;
        return true ;
    }

    public function upgrade($old_version)
    {
        if ( $old_version === '1.0' ) { // Upgrade 1.0 -> 1.1
            $custom_setting = array(
                'events_user_event_approval' => array(
                    'slug'        => 'events_user_event_approval',
                    'title'       => 'User Event or Interest Approval',
                    'description' => 'If the user created events or interest must be approved by the admin before display in public.',
                    'default' => '1',
                    'value' => '1',
                    'type' => 'select',
                    'options' => '1=Yes|0=No',
                    'is_required' => true,
                    'is_gui'      => true,
                    'module'      => 'eventsmanager',
                    'order'       => 10)
            );
            $this->load->library('settings') ;
            foreach ( $custom_setting as $setting ){
                $this->settings->add($setting) ;
            }
        }
        return true ;
    }

    public function help()
    {
        // You could include a file and return it here.
        return "Please mail at ankitvishwakarma@sify.com for any further assistance." ;
    }

}

/* End of file details.php */
