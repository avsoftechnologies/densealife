<!--{{theme:partial name='blocks/common'}}-->
<div class="comman-box clearfix">
    <div class="comman-heading">My Favorites</div>
    <ul class="stream">
        {{eventsmanager:favorites user_id=_user:id}}
        <li>
            <h2>{{title}}</h2>
            <span class="image">{{ eventsmanager:thumb name="{{ thumbnail }}" }}
                <div class="display-none hover-aera">
                        <a href="" class="float-left star-aera">Star</a>
                        {{trends:link_follow entry_id=entry_id}}
                </div>
            </span>
            <div><a href="" class="float-right">{{follow_count}} Followers</a></div>
            <span class="block new-star"><a href="" class="stars comman-star">{{star_count}} Stars</a></span> </li>
        {{/eventsmanager:favorites}}
    </ul>
</div>
