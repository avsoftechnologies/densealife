<table border='0' width='400' cellspacing='0' cellpadding = '0'>
    <tr>
        <td rowspan="2" class='txt-center'>
            {{user:profile_pic user_id='{{data:sender_id}}'}} 
        </td>
        <td style='vertical-align: top;'>
            <b>{{data:sender_name}}</b>
            sent you a message
            <br/>
            <span style='color:gray;'>
             {{generic:time_ago datetime='{{data:created_on}}'}}
            </span>
        </td>
        <td style='vertical-align: top;' class='noti ml0'>
            <a href='/messages/{{data:username}}'>Show Message</a>
        </td>
    </tr>
</table>