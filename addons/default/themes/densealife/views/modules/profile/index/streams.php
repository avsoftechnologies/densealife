<div class="comman-heading">Stream</div>
<div class="search-box">
    <label for="SearchBox"></label>
    <input type="text" name="stream_search" id="stream_search_main" placeholder="Search streams"/>
    <!--<button type="submit" id='go-stream-search'>Go</button>-->
</div>
<ul class="stream" id="stream_search_result_main">
    <?php if ( !empty($events) ): ?>
        <?php foreach ( $events as $event ): ?>
            <?php echo load_view('profile', '/index/loop', array( 'data' => $event )); ?>
        <?php endforeach; ?>
    <?php else: ?>
        <li>No records Found</li>
    <?php endif; ?>
</ul>

<script>
            $(document).ready(function(){
                $('#stream_search_main').keyup(function(){
                   
                   if($(this).val().length > 3){
                       $('#stream_search_result_main').animate({'opacity': '0.3'});
                       
                       $('#stream_search_result_main').load('/eventsmanager/search/'+$(this).val(), function(response){
                           $('#stream_search_result_main').animate({'opacity': '1'});
                       });
                   } 
                });
            });
        </script>
