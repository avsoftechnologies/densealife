<?php $user_id = empty($user_id) ? $this->current_user->id : $user_id; ?>
<?php $entry_id = empty($entry_id) ? $data->id : $data->entry_id; ?>
<li class="txt-center">
    <span class="f-bold"><a href="/eventsmanager/<?php echo $data->slug; ?>" title="<?php echo $data->title; ?>"><?php echo $data->title; ?></a></span>
    <span class="image">
        <a href="/eventsmanager/<?php echo $data->slug; ?>" title="<?php echo $data->title; ?>" >
            {{ eventsmanager:thumb name="<?php echo $data->thumbnail;?>" }}
            <div class="display-none hover-aera">
                {{button:star_event event_id='<?php echo $entry_id;?>'}}
                {{button:follow_event event_id='<?php echo $entry_id;?>' class='float-right ctrl_trend'}}
            </div>
        </a>
    </span> 
    <table width="100%" border="0">
        <tr>
            <td width="50%"><a href="" class="float-left"><span class="d-inline count_star_<?php echo $entry_id;?>"><?php echo $data->star_count; ?></span>  &nbsp;Stars</a>  </td>
            <td  width="50%"><a href="" class="float-right"> <span class="d-inline count_follow_<?php echo $entry_id;?>"><?php echo $data->follow_count; ?></span> &nbsp;Followers</a></span> </td>
        </tr>
    </table>
</li>