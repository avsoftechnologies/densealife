<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author		PyroCMS Dev Team
 * @package		PyroCMS\Core\Modules\Users\Models
 */
class Profile_m extends MY_Model
{
	/**
	 * Get a user profile
	 *
	 * 
	 * @param array $params Parameters used to retrieve the profile
	 * @return object
	 */
	public function get_profile($params = array())
	{
		$query = $this->db->get_where('profiles', $params);

		return $query->row();
	}
	
        public function get_profiles($where = array())
        {
            	$query = $this->db->get_where('profiles', $params);
                return $query->result();
        }
	/**
	 * Update a user's profile
	 *
	 * 
	 * @param array $input A mirror of $_POST
	 * @param int $id The ID of the profile to update
	 * @return bool
	 */
	public function update_profile($input, $id)
	{
		$set = array(
			'gender'		=> 	$input['gender'],
			'bio'			=> 	$input['bio'],
			'phone'			=>	$input['phone'],
			'mobile'		=>	$input['mobile'],
			'address_line1'	=>	$input['address_line1'],
			'address_line2'	=>	$input['address_line2'],
			'address_line3'	=>	$input['address_line3'],
			'postcode'		=>	$input['postcode'],
	 		'website'		=>	$input['website'],
			'updated_on'	=>	now()
		);

		if (isset($input['dob_day']))
		{
			$set['dob'] = mktime(0, 0, 0, $input['dob_month'], $input['dob_day'], $input['dob_year']);
		}

		// Does this user have a profile already?
		if ($this->db->get_where('profiles', array('user_id' => $id))->row())
		{
			$this->db->update('profiles', $set, array('user_id'=>$id));
		}	
		else
		{
			$set['user_id'] = $id;
			$this->db->insert('profiles', $set);
		}
		
		return true;
	}
        
        public function get_most_active_users($limit)
        {
            $result = $this->select($this->_table.'.* ,count(ef.follow) as `order`')
                    ->join('event_followers as ef','ef.user_id = '.$this->_table.'.user_id','inner')
                    ->group_by('ef.user_id')
                    ->order_by('`order`','desc')
                    ->order_by('display_name', 'desc')
                    ->limit($limit)
                    ->get_all();
            return $result; 
                    
        }
        
        public function get_profile_pic($user_id, $dim='70')
        {
            $profile = $this->select('profile_pic, display_name')->get_by('user_id', $user_id);
            $width  = $dim;
            $height  = $dim;
            $mode  = 'fit';
            if($profile->profile_pic){
                return '<a href="/user/'.$profile->display_name.'">'.img(array( 'src' => 'files/thumb/' . $profile->profile_pic . '/'.$width.'/'.$height.'/'.$mode)).'</a>';
            }else{
                return img(array('src' => '/assets/images/no-profile-pic.png', 'width' => $width, 'height' =>$height));
            }
        }
        
        public function get_user_data($user_id = null)
        {
            $user_id = $user_id!='' ? $user_id : $this->current_user->id; 
        }
        
        private function _get_post_count()
        {
            
        }
        
        private function _get_followers_count()
        {
            
        }
        private function _get_followers()
        {
            
        }
        private function _get_following_count()
        {
            
        }
        private function _get_followings()
        {
            
        }
        private function _get_friend_count()
        {
            
        }
        private function _get_friends()
        {
            
        }
        private function _get_my_events()
        {
            
        }
        private function _get_my_interests()
        {
            
        }
        private function _get_my_albums()
        {
            
        }
        private function _get_my_photos()
        {
            
        }
}