<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        {{ theme:partial name='metadata' }}
    </head>

    <body>
        {{ integration:analytics }}
        <?php $module_path = BASE_URL . $this->module_details['path']; ?>
        <div id="wepper-inner"> 
            <!--Start Header-->
            <header id="wepper">
                {{ theme:partial name='head' }}   
            </header>
            <!--End Header--> 
            <!--Start Body Container-->
            <div id="body-container-inner" class="clearfix"> 
                <div class="links-header">
                    <ul class="comman-links link-header-event">
                        <li data-page="events"><a href="/densealife-page/#events">Events</a></li>
                        <li data-page="trending"><a href="/densealife-page/#trending">Trending</a></li>
                        <li data-page="upcoming"><a href="/densealife-page/#upcoming">Upcoming</a></li>
                        <li data-page="favorite"><a href="/densealife-page/#favorite">Favorites</a></li>
                    </ul>
                </div>
                <!--Start center-body-container-->
                <div class="message-bodyinnre-container">
                    {{ theme:partial name="content" }}
                </div>
                <!--End center-body-container--> 
                <!--Start right-body-container-->
                <div class="right-bodyinnre-container">
                    <div class="comman-box">
                        <span class="heading-comman">Discover Interest</span>
                        <ul class="friends clearfix">
                            {{ eventsmanager:trending limit="8" type='interest' user_id=user:id}}
                            <li>
                                <a href="{{ url:site uri='eventsmanager/{{slug}}' }}" title="{{ title }}" >{{ eventsmanager:thumb name="{{ thumbnail }}" }}</a>
                            </li>
                            {{ /eventsmanager:trending }}
                        </ul>
                    </div>
                    <div class="comman-box">
                        <span class="heading-comman">Friends Events</span>
                        <ul class="friends clearfix">
                            {{friend:events user_id=user:id}}
                            <li>
                                <a href="{{ url:site uri='eventsmanager/{{slug}}' }}" title="{{ title }}" >{{ eventsmanager:thumb name="{{ thumbnail }}" }}</a>
                            </li>
                            {{/friend:events}}
                        </ul>
                    </div>
                    {{theme:partial name="blocks/stream"}}
                </div>
                <!--End Right-body-container--> 
            </div>
            <!--End Body Container-->
            <footer> 
                {{ theme:partial name="footer" }}   
            </footer>
        </div>
        {{asset:js file='customer.js'}}
        {{asset:render}}
    </body>
</html>
