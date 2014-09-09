<?php
/**
 * 
 * @param int $current_user_id 
 */
function add_friend_button($user_id, $current_user_id)
{
        if($current_user_id != ci()->current_user->id){
            $current_user_id = ci()->current_user->id;
        }
        ci()->load->model('friend/friend_m');
        $friendship = ci()->friend_m->is_friend($user_id, $current_user_id);    
        
     
        if($friendship):
        switch($friendship->status){
            case 'accepted':
                    echo '<button class="common right btn_add_friend_'.$user_id.'" onclick="friend.unfriend('.$user_id.');" onmouseover="$(this).text(\'Unfriend\')" onmouseout="$(this).text(\'Friends\')">Friends</button>'; 
                break;
            case 'initiated':
                echo '<button class="common right btn_add_friend_'.$user_id.'" onclick="friend.accept('.$user_id.');">'.$friendship->status_label.'</button>'; 
                break;
            default:
                echo '<button class="common right btn_add_friend_'.$user_id.'" onclick="friend.add('.$user_id.');">+ Add Friends</button>'; 
                break;
        } 
        elseif($user_id!=ci()->current_user->id):
            echo '<button class="common right btn_add_friend_'.$user_id.'" onclick="friend.add('.$user_id.');">+ Add Friends</button>'; 
        endif;
}

