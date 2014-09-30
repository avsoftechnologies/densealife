<span class="img-left">
    <a href="#">
        {{user:profile_pic user_id='{{data:sender_id}}'}}
    </a>
</span>
<span class="center-text">
    <span class="fl">
        <strong>{{data:sender}}</strong> {{ helper:lang line="profile:friend_initiated" }}
    </span>
    <span>
        {{button:friend user_id='{{data:sender_id}}'}}
    </span>
</span>
<span class="time-log">{{generic:time_ago datetime='{{data:created_on}}'}}</span>