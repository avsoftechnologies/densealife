<?php

defined('BASEPATH') or exit('No direct script access allowed') ;

/**
 *
 *
 * @author      Ankit Vishwakarma <ankitvishwakarma@sify.com>
 * @package 	PyroCMS
 * @subpackage 	Events Manager Module
 * @category 	Modules
 * @license 	Copyright
 */
class Friend_m extends MY_Model
{

    CONST AWAITING_RESPONSE     = 'awaiting_response' ;
    CONST REQUEST_SENT          = 'request_sent' ;
    
    CONST STATUS_INITIATED = 'initiated';
    CONST STATUS_ACCEPTED = 'accepted';
    CONST STATUS_BROKEN = 'broken';
    

    private $_salt_length = 30 ;

    public function __construct()
    {
        parent::__construct() ;
        $this->_table = 'friend_list' ;
    }

    public function salt()
    {
        return substr(md5(uniqid(rand(), true)), 0, $this->_salt_length) ;
    }

    public function get_status_label_mapping($response)
    {
        $mapping = array(
            self::AWAITING_RESPONSE => 'Awaiting Response',
            self::REQUEST_SENT      => 'Request Sent',
        ) ;
        return array_key_exists($response, $mapping) ? $mapping[$response] : 'Invite Friend' ;
    }

    public function send_friend_request($friend_id)
    {
        $exist = (bool) parent::count_by(array('user_id' => $this->current_user->id, 'friend_id' => $friend_id,'status!=' => self::STATUS_BROKEN));
        if ( !$exist ) {
            $thread = $this->salt();
            try {
                $inserted_id = parent::insert(array(
                            'user_id'   => $this->current_user->id,
                            'friend_id' => $friend_id,
                            'thread'    => $thread,
                            'status'    => 'initiated' )
                        );
            } catch ( Exception $e ) {
                echo $e->getMessage();
            }
            return $this->_add_into_notification($friend_id, $thread);
        }else{
            return false;
        }
        
    }
    
    public function respond_friend_request($friend_id, $status = 'accepted')
    {
        $where = array('friend_id' => $this->current_user->id, 'user_id' => $friend_id);
        $this->db->update('friend_list',array('status' => $status,'accepted_at' => date('Y-m-d H:i:s', now())),$where);
        $rs = $this->get_by($where);
        $thread = $rs->thread; 
        $this->db->update('notifications', array( 'status' => 'seen' ), array( 'thread' => $thread ));
        return $this->_add_into_notification($friend_id,$thread,'friend');
    }

    private function _add_into_notification($friend_id, $thread, $type='friend')
    {
        $this->db->insert($this->db->dbprefix('notifications'), array(
            'sender_id'=> $this->current_user->id,
            'rec_id'   => $friend_id,
            'type'      => $type,
            'thread' => $thread)
        ) ;
        
        return $this->db->insert_id(); 
    }

    public function response($request_id, $response)
    {
        
    }
    
    public function get_friend_suggestions($limit = null)
    { 
         $rs = $this->query("
                        SELECT P.user_id as user_id,P.display_name as name, username, F.status , if(F.user_id ='" . $this->current_user->id . "',s.requester,s.responder) as status_label
                        FROM " . $this->db->dbprefix('profiles') . " AS P 
                        LEFT JOIN " . $this->db->dbprefix('friend_list') . " AS F ON (P.user_id = F.friend_id OR P.user_id = F.user_id) AND (F.user_id ='" . $this->current_user->id . "' OR F.friend_id = '" . $this->current_user->id . "')
                        LEFT JOIN default_friend_status AS s ON s.status = F.status
                        INNER JOIN " . $this->db->dbprefix('users') . " AS u
                        ON u.id = P.user_id
                        WHERE P.user_id IN(
                                            SELECT t.user_id
                                            FROM " . $this->db->dbprefix('trends') . " AS t
                                            WHERE entry_id
                                            IN (
                                                SELECT entry_id
                                                FROM " . $this->db->dbprefix('trends') . " AS def2
                                                WHERE def2.user_id ='" . $this->current_user->id . "'
                                            )
                                            AND t.user_id!='" . $this->current_user->id . "'
                                        )
                        AND F.status!='accepted' OR F.status IS NULL
                        AND P.user_id!='" . $this->current_user->id . "'
                        
                        LIMIT {$limit}"
        ) ;
        $result = $rs->result();
        shuffle($result);
        return  $result;
        
    }

    /**
     * Get the list of all the active profiles but logged in user who have same 
     * follow or same favourite or same stars and still not friends
     * @param type $limit
     */
    public function get_friend_suggestions_old($limit = null)
    {
        
        $rs = $this->query("
                        SELECT P.user_id as user_id, P.display_name as name, F.status
                        FROM " . $this->db->dbprefix('profiles') . " AS P 
                        LEFT JOIN " . $this->db->dbprefix('friend_list') . " AS F ON P.user_id = F.friend_id
                        
                        WHERE P.user_id IN(
                                            SELECT t.user_id
                                            FROM " . $this->db->dbprefix('trends') . " AS t
                                            LEFT JOIN " . $this->db->dbprefix('friend_list') . " AS fl ON t.user_id = fl.user_id
                                            AND fl.user_id !='" . $this->current_user->id . "'
                                            WHERE entry_id
                                            IN (
                                                SELECT entry_id
                                                FROM " . $this->db->dbprefix('trends') . " AS def2
                                                WHERE def2.user_id ='" . $this->current_user->id . "'
                                                GROUP BY entry_id
                                            )
                                            AND t.user_id !='" . $this->current_user->id . "'
                                            AND (fl.user_id IS NULL)
                                            
                                            GROUP BY t.user_id
                                        )
                        LIMIT {$limit}"
        ) ;


        $return = $rs->result() ;
        //echo $this->db->last_query(); exit; 
        shuffle($return) ;
        return $return ;
    }

    public function get_mutual_friends($user_id)
    {
        $query = $this->query("
                SELECT P.user_id as user_id,P.display_name as name, username
                FROM " . $this->db->dbprefix('profiles') . " AS P 
                INNER JOIN " . $this->db->dbprefix('friend_list') . " AS FL1 
                ON (FL1.user_id =P.user_id OR FL1.friend_id = P.user_id) 
                AND (FL1.user_id = $user_id OR FL1.friend_id= $user_id) 
                AND (FL1.status='accepted')
                INNER JOIN " . $this->db->dbprefix('users') . " AS u
                ON u.id = P.user_id
                WHERE friend_id in (
                                        SELECT user_id 
                                        FROM " . $this->db->dbprefix('friend_list') . "
                                        WHERE (user_id = $user_id AND friend_id = {$this->current_user->id}) 
                                        AND status='accepted' 
                                        
                                        UNION 
                                        
                                        SELECT friend_id 
                                        FROM " . $this->db->dbprefix('friend_list') . " 
                                        WHERE (user_id = {$this->current_user->id} and friend_id=$user_id) 
                                        AND status='accepted'
                                    ) 
            ");
                                        
        $rs =  $query->result();
        return $rs;
    }
    public function get_mutual_friends_old($limit = null)
    {

        // get the user ids the current user is in their friend list.
        $this->select('user_id')
                ->from('friend_list')
                ->where('friend_id', $this->current_user->id) ;
        $where_clause = $this->get_compiled_select() ;

        $this->select('DISTINCT COUNT(friend_id) as n, p.*')
                ->from('friend_list as fl')
                ->join('profiles as p', 'p.user_id=fl.friend_id', 'inner')
                ->where_in('p.user_id', $where_clause)
                ->where('friend_id!=', $this->current_user->id)
                ->group_by('friend_id')
                ->having('n>=', $this->current_user->id) ;
        if ( !is_null($limit) ) {
            $this->limit($limit) ;
        }
        return $this->get_all() ;
    }
    
    /**
     * 
     * @param int $uid id of the person to check against
     * @return boolean 
     */
    public function is_friend($uid, $curent_user_id = null)
    {
        $curent_user_id = $curent_user_id =='' ? $this->current_user->id : $curent_user_id;
        $query = $this->query("
                SELECT fl.status , user_id as sender, friend_id as receiver,  if(user_id ='" . $curent_user_id . "' ,s.requester, s.responder) as status_label
                FROM {$this->db->dbprefix('friend_list')} as fl
                INNER JOIN {$this->db->dbprefix('friend_status')} as s
                ON s.status = fl.status
                WHERE (user_id = $uid AND friend_id = {$curent_user_id})
                OR (friend_id = $uid AND user_id = {$curent_user_id})
            ");
        $rs = $query->row();
        return $rs;
    }
    
    
    /**
     * fetch all the friends following the same event as passed as $event_id
     * @param type $event_id
     * @param type $user_id
     * @return type
     */
    public function get_follower_friends($event_id, $user_id)
    {
        $friend_ids = "SELECT "
                . "CASE "
                . "  WHEN "
                . "      user_id = '" . $user_id . "' THEN friend_id "
                . "  WHEN "
                . "      friend_id = '" . $user_id . "' THEN user_id "
                . "END AS fid"
                . " FROM default_friend_list"
                . " WHERE status='accepted' "
                . " AND (user_id='" . $user_id . "' OR friend_id='" . $user_id . "')";

        $following_events = "SELECT"
                . " DISTINCT p.* "
                . "FROM default_trends as t"
                . " LEFT JOIN default_profiles as p "
                . " ON p.user_id = t.user_id"
                . " WHERE entry_id='" . $event_id . "'"
                . " AND p.user_id IN (" . $friend_ids . ")"
                . " AND follow='true'";

        $rs = $this->query($following_events)->result();  
        return $rs; 
    }

    
    public function get_friends($user_id, $select = "`p`.*, `u`.`username`")
    {
        if(!is_null($user_id)){
            $var1    = "";
            $var1 .= "SELECT {$select} ";
            $var1 .= "FROM   `default_profiles` AS `p` ";
            $var1 .= "       INNER JOIN `default_users` AS `u` ";
            $var1 .= "               ON `p`.`user_id` = `u`.`id` ";
            $var1 .= "WHERE  `u`.`id` IN (SELECT friend_id ";
            $var1 .= "                    FROM   default_friend_list ";
            $var1 .= "                    WHERE  user_id = $user_id ";
            $var1 .= "                           AND status = 'accepted' ";
            $var1 .= "                    UNION ";
            $var1 .= "                    SELECT user_id ";
            $var1 .= "                    FROM   default_friend_list ";
            $var1 .= "                    WHERE  friend_id = $user_id ";
            $var1 .= "                           AND status = 'accepted') "
                    . " AND u.id != '". $this->current_user->id."'";
            $query  = $this->query($var1); 
            $rs = $query->result();
            return $rs; 
        }
    }
    
    public function get_friend_recently_followed_entry($user_id, $type = 'event', $limit = 8)
    {
        $friend_ids = "SELECT "
                . "CASE "
                . "  WHEN "
                . "      user_id = '" . $user_id . "' THEN friend_id "
                . "  WHEN "
                . "      friend_id = '" . $user_id . "' THEN user_id "
                . "END AS fid"
                . " FROM default_friend_list"
                . " WHERE status='accepted' "
                . " AND (user_id='" . $user_id . "' OR friend_id='" . $user_id . "')";
        
        $events = "SELECT DISTINCT E.* "
                . " FROM default_trends as T"
                . " INNER JOIN default_events as E ON E.id = T.entry_id "
                . " WHERE T.user_id IN (".$friend_ids.")"
                . " AND T.entry_type = '".$type."'"
                . " AND T.follow = 'true'"
                . " ORDER BY T.created_on DESC"; 
        if($limit !='') {
            $events.= " LIMIT $limit ";
        }
        $query  = $this->query($events); 
        $rs = $query->result();
        return $rs; 

    }
    /**
     * get_events will be used to fetch all the events followed by the logged in user's friends. 
     * @param type $user_id
     * @param type $limit
     */
    public function get_events($user_id, $limit = null)
    { 
        $this->get_friend_recently_followed_entry($user_id, 'event', $limit); 
        $friends = $this->get_friends($user_id);
        $friend_ids = array();
        if(!empty($friends)){
            foreach($friends as $friend){
                $friend_ids[]= $friend->user_id; 
            }
            $this->load->model('trends/trend_m');
            return $this->trend_m->get_follows($friend_ids, $limit);
        }
    }
    
    public function unfriend($friend_id)
    {
        $this->where("(user_id = '".$friend_id."' AND friend_id='".$this->current_user->id."') "
                . "OR (friend_id = '".$friend_id."' AND user_id='".$this->current_user->id."')");
        $this->db->update($this->_table, array('status' => 'broken','broken_at' => date('Y-m-d H:i:s', now())));
        return true; 
        
    }
    
    public function get_pending_requests(){
        
        $query = $this->query("SELECT * FROM `default_friend_list` as fl inner join default_profiles as p on p.user_id = fl.user_id
where fl.status = 'initiated' and fl.friend_id = {$this->current_user->id}");
        return $query->result();
    }
    
}
