<!--{{theme:partial name='blocks/common'}}-->
<div class="comman-box clearfix">
    <div class="comman-heading">My Interests</div>
    <ul class="stream">
        {{eventsmanager:get_all_events user_id=_user:id entry_type='interest'}}
        <li>
            <h2>{{title}}</h2>
            <span class="image">{{ eventsmanager:thumb name="{{ thumbnail }}" }}
                <div class="display-none hover-aera">
                        {{button:star_event event_id="{{entry_id}}"}}
                        {{button:follow_event event_id="{{entry_id}}" class="ctrl_trend fr"}}
                </div>
            </span>
            <div><a href="" class="float-right">{{follow_count}} Followers</a></div>
            <span class="block new-star"><a href="" class="stars comman-star">{{star_count}} Stars</a></span> </li>
        {{/eventsmanager:get_all_events}}
    </ul>
</div>