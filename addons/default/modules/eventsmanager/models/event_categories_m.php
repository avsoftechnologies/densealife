<?php

defined('BASEPATH') or exit('No direct script access allowed') ;

/**
 * Categories model
 *
 * @author  Ankit Vishwakarma <ankitvishwakarma@sify.com>
 * @package Addons\Default\Modules\Eventsmanger\Models
 */
class Event_categories_m extends MY_Model
{

    /**
     * Insert a new category into the database
     *
     * @param array $input The data to insert
     * @param bool  $skip_validation
     *
     * @return string
     */
    protected $_table = 'event_categories' ;

    const ACTIVE   = 1 ;
    const INACTIVE = 0 ;

    public function insert($input = array(), $skip_validation = false)
    {
        parent::insert(array(
            'title'       => $input['title'],
            'slug'        => $input['slug'],
            'description' => $input['description'],
            'author'      => $input['author'],
            'parent_id'   => !empty($input['parent_id']) ? $input['parent_id'] : 0
        )) ;

        return $input['title'] ;
    }

    /**
     * Update an existing category
     *
     * @param int   $id    The ID of the category
     * @param array $input The data to update
     * @param bool  $skip_validation
     *
     * @return bool
     */
    public function update($id, $input, $skip_validation = false)
    {
        return parent::update($id, array(
                    'title'       => $input['title'],
                    'slug'        => $input['slug'],
                    'description' => $input['description'],
                    'author'      => $input['author'],
                    'parent_id'   => !empty($input['parent_id']) ? $input['parent_id'] : 0
        )) ;
    }

    /**
     * Callback method for validating the title
     *
     * @param string $title The title to validate
     * @param int    $id    The id to check
     *
     * @return mixed
     */
    public function check_title($title = '', $id = 0)
    {
        return ( bool ) $this->db->where('title', $title)
                        ->where('id != ', $id)
                        ->from($this->_table)
                        ->count_all_results() ;
    }

    /**
     * Callback method for validating the slug
     *
     * @param string $slug The slug to validate
     * @param int    $id   The id to check
     *
     * @return bool
     */
    public function check_slug($slug = '', $id = 0)
    {
        return ( bool ) $this->db->where('slug', $slug)
                        ->where('id != ', $id)
                        ->from($this->_table)
                        ->count_all_results() ;
    }

    /**
     * Insert a new category into the database via ajax
     *
     * @param array $input The data to insert
     *
     * @return int
     */
    public function insert_ajax($input = array())
    {
        return parent::insert(array(
                    'title' => $input['title'],
                    //is something wrong with convert_accented_characters?
                    //'slug'=>url_title(strtolower(convert_accented_characters($input['title'])))
                    'slug'  => url_title(strtolower($input['title']))
        )) ;
    }

    public function get_all_categories($limit = null, $offset = null)
    {
        $this->db
                ->select('a.*, b.title as category_title, count( e.id ) as event_count ')
                ->from($this->_table . ' as a')
                ->join($this->_table . ' as b', 'b.id=a.parent_id', 'left')
                ->join('events as e', 'e.sub_category_id=a.id', 'left')
                ->where(array( 'a.status' => '1' ))
                ->group_by('a.id')
                ->order_by('a.title') ;
        if ( !is_null($limit) ) {
            $this->db->limit($limit) ;
        }
        if ( !is_null($offset) ) {
            $this->db->offset($offset) ;
        }
        $categories = $this->db
                ->get()
                ->result_array() ;
        return $categories ;
    }
    
    public function get_parent_categories(){
        return $this->order_by('title')
                    ->get_many_by(array( 'parent_id' => 0, 'status' => '1' )) ;
    }
    
    public function get_sub_categories($parent_id){
         return $this->order_by('title')
                    ->get_many_by(array( 'parent_id' => $parent_id, 'status' => '1' )) ;
    }

    public function is_category_associated($id){
        $count = $this->db->select('count(*) as count')
                ->from('events')
                ->where('category_id',$id)
                ->or_where('sub_category_id',$id)
                ->get()
                ->row_array();
        return $count['count'] ? true : false;  
    }
}
