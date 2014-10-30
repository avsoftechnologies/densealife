<ul>
    {{notifications}}
    <li>
    <span>
        {{user:profile_pic user_id='{{sender_id}}'}}
    </span>
    <span class='fl ml20'>
        <strong>{{sender}}</strong>
        {{ helper:lang line="profile:message_received" }}
    </span>
</li>
{{/notifications}}
</ul>