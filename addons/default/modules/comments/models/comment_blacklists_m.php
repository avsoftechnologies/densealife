<?php

/*
* Comment_blacklist_m
* @author Matt Frost
*
* Model for the comment blacklist feature
*/

class Comment_blacklists_m extends MY_Model {
	
	private $data;
   
	public function __construct()
	{
		$this->_table = $this->db->dbprefix('comment_blacklists');
	}

	public function save($data)
	{
		if ($this->_get_count($data) < 1)
		{
			return parent::insert($data);
		}
	}
	
	private function _get_count($data)
	{
            return $this->db->where($data)->count_all_results('comment_blacklists');
	}

	public function is_blacklisted($author, $user)
        {
            $data = array(
                'author_id'       => $author,
                'blocked_user_id' => $user,
                'status'          => 'block'
            );
            return (bool) $this->_get_count($data);
        }

}
