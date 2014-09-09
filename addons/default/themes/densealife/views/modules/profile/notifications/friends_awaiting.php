<ul>
    {{notifications}}
    <li>
    <span>
        {{user:profile_pic user_id='{{sender_id}}'}}
    </span>
    <span class='fl ml20'>
        {{button:friend user_id='{{sender_id}}'}}
    </span>
</li>
{{/notifications}}
</ul>