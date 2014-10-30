<ul>
    {{notifications}}
    <li>
        <span>
            {{user:profile_pic user_id='{{sender_id}}'}}
        </span>
        <span>
            {{sender_name}} wants to be your friend.
        </span>
        <span>
            {{button:friend user_id='{{sender_id}}'}}
        </span>
    </li>
    {{/notifications}}
</ul>