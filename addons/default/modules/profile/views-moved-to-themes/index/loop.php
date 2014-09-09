<li>
    <h2><a href="/eventsmanager/about/<?php echo $data->slug; ?>" title="<?php echo $data->title; ?>" ><?php echo $data->title; ?></a></h2>
    <a href="/eventsmanager/about/<?php echo $data->slug; ?>" title="<?php echo $data->title; ?>" >
        <span class="image">
            {{ eventsmanager:thumb name="<?php echo $data->thumbnail; ?>" }}
            <div class="display-none hover-aera"><a href="" class="float-left star-aera">Star</a>  <button type="button" class="float-right">follow</button></div>
        </span> 
    </a>
    <div><a href="" class="float-right"><?php echo $data->follow_count; ?> Followers</a></div>
    <span class="block new-star"><a href="" class="stars comman-star"><?php echo $data->star_count; ?> Stars</a></span>  
</li>