<?php

class Message_m extends MY_Model
{

    protected $_table = 'messages';

    const MESSAGE_STATUS_READ    = 'read';
    CONST MESSAGE_STATUS_UNREAD  = 'unread';
    CONST MESSAGE_STATUS_TRASHED = 'trashed';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('users/user_m');
    }
    
    private function _insert($data, $skip_validation = false)
    {
            $data['status']  = 'unread';
            $data['created_at'] = date('Y-m-d H:i:s', now());
            return parent::insert($data, $skip_validation);   
    }
    public function insert($data, $skip_validation = false)
    {
        if(isset($data['action']) && $data['action'] == 'new_message'){
            $rec_usernames = explode(',', $data['to']);
            $rec_usernames = array_filter($rec_usernames);
            unset($data['to'], $data['action']);
            foreach($rec_usernames as $username){
                if(!empty($username)){
                    $user = $this->user_m->get_by('username',$username);
                    $data['sender_id'] = $this->current_user->id; 
                    $data['rec_id'] = $user->id;

                    $this->_insert($data , $skip_validation);
                }
            }
            return true; 
        }else{
            return $this->_insert($data , $skip_validation);
        }
    }

    public function update($primary_value, $data, $skip_validation = false)
    {
        parent::update($primary_value, $data, $skip_validation);
    }

    public function delete($id)
    {
        parent::delete($id);
    }

    public function get_senders($rec_id = null, $limit = 10)
    {
        $rec_id  = $rec_id == '' ? $this->current_user->id : $rec_id;
        $senders = $this->select('sender_id')
                            ->distinct()
                            ->order_by('created_at', 'desc')
                            ->limit($limit)
                            ->get_many_by(
                            array(
                                'rec_id' => $rec_id
                            )
                    );
        return $senders;
    }

    public function get_conversation($sender_id, $rec_id = null)
    {
        $rec_id = $rec_id == '' ? $this->current_user->id : $rec_id;
        $msgs   = $this->get_many_by("(rec_id = {$rec_id} and sender_id = {$sender_id} ) "
        . "or (sender_id = {$rec_id} and rec_id = {$sender_id})");
        return $msgs;
    }

    public function count_unread($rec_id = null)
    {
        $rec_id = $rec_id == '' ? $this->current_user->id : $rec_id;
        return $this->count_by(array( 'rec_id' => $rec_id, 'status' => self::MESSAGE_STATUS_UNREAD ));
    }

    public function get_recent_sender_conversations($rec_id = null)
    {
        $rec_id  = $rec_id == '' ? $this->current_user->id : $rec_id;
        $senders = $this
                ->order_by('created_at', 'desc')
                ->get_many_by(
                array(
                    'rec_id' => $rec_id
                )
        );
        //echo $this->last_query();
        return $senders;
    }
    
    public function get_recent_sender_receiver()
    {
        $recent = $this->query(
                "SELECT sender_id , created_at "
                . "FROM `default_messages` "
                . "WHERE rec_id = {$this->current_user->id}  "
                . "UNION "
                . "SELECT rec_id , created_at "
                . "FROM `default_messages`"
                . "WHERE sender_id = {$this->current_user->id} "
                . "ORDER BY created_at DESC LIMIT 1")
                ->row();
        $sender = null;
        if($recent){
            $sender =  $recent->sender_id;
        }
        return $sender;
    }
}
