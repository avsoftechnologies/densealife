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
                <div class="top-scrip"> <span><a href="/densealife-page">{{ theme:image file="logoname.png" width="111" height='24'}} </a></span>
                    <ul class="innerpages">
                        <li title="Friend Request">{{ theme:image file="friendreq.png" alt="Friend Request" }}</li>
                        <li title="Message">{{ theme:image file="msg.png" alt="Message" }}</li>
                        <li title="Notification">{{ theme:image file="notification.png" alt="Notification" }}</li>
                        <li title="My Account">{{ theme:image file="myaccount.png" alt="My Account" }}</li>
                    </ul>
                </div>
            </header>
            <!--End Header--> 
            <!--Start Body Container-->
            <div id="body-container-inner" class="clearfix"> 
                <div class="links-header">
                    <ul class="comman-links">
                        <li class="<?php echo (($this->router->fetch_method()=='events') ? ' active ' : '');?>page-event" data-page="events"><a href="javascript:void(0);">Events</a><?php //echo anchor('' , 'Events') ; ?></li>
                        <li class="<?php echo (($this->router->fetch_method()=='trending') ? ' active ' : '');?>page-trending" data-page="trending"><a href="javascript:void(0);">Trending</a><?php //echo anchor('profile/page/upcoming' , 'Upcoming') ; ?></li>
                        <li class="<?php echo (($this->router->fetch_method()=='upcoming') ? ' active ' : '');?>page-upcoming" data-page="upcoming"><a href="javascript:void(0);">Upcoming</a><?php //echo anchor('profile/page/upcoming' , 'Upcoming') ; ?></li>
                        <li class="<?php echo (($this->router->fetch_method()=='favorites') ? 'active ' : '');?>page-favorite" data-page="favorite"><a href="javascript:void(0);">Favorites</a><?php //echo anchor('profile/page/favorites' , 'Favorites') ; ?></li>
                    </ul>
                </div>
                <!--Start left-body-container-->
                <div class="left-bodyinnre-container">
                    <div class="logo-densea-life-inner">{{ theme:image file="logo.png"}}</div>
                    <span class="user-profile">{{ user:display_name}}<a class="fancybox fancybox.ajax" href="/edit-profile">Edit</a></span>
                    <div class="comman-box">
                        <span class="heading-comman">Information</span>
                        <ul>
                            <li title="Message">{{ theme:image file="msg.png"}}<span class="left-links"><a href="">Message</a></span></li>
                            <li title="Photos">{{ theme:image file="photos.png"}}<span class="left-links"><a href="">Photos</a></span></li>
                            <li title="Events">{{ theme:image file="events-icon.png"}}<span class="left-links"><a href="">Followers 2859</a></span></li>
                            <li title="Friend Request">{{ theme:image file="friendreq.png"}}<span class="left-links"><a href="">Find Friends</a></span></li>
                        </ul>
                    </div>
                    <div class="comman-box">
                        <span class="heading-comman">Events</span>
                        <ul>
                            <li title="Trending" class="page-trending">{{ theme:image file="trending.png"}}<span class="left-links"><a href="javascript:void(0);">Trending</a></span></li>
                            <li title="Favorite" class="page-favorite">{{ theme:image file="favrate-icon.png"}}<span class="left-links"><a href="javascript:void(0);">Favorite</a></span></li>
                            <li title="Upcoming" class="page-upcoming">{{ theme:image file="upcoming.png"}}<span class="left-links"><a href="javascript:void(0);">Upcoming</a></span></li>
                        </ul>
                    </div>
                    
                     <div class="comman-box">
                        <span class="heading-comman">Interests</span>
                        <ul>
                            <li title="Trending" class="page-trending">{{ theme:image file="trending.png"}}<span class="left-links"><a href="javascript:void(0);">Trending</a></span></li>
                            <li title="popular" class="page-popular">{{ theme:image file="upcoming.png"}}<span class="left-links"><a href="javascript:void(0);">Popular</a></span></li>
                            <li title="Favorite" class="page-favorite">{{ theme:image file="favrate-icon.png"}}<span class="left-links"><a href="javascript:void(0);">Favorite</a></span></li>
                        </ul>
                    </div>
                    <div class="comman-box">
                        <span class="heading-comman">Music</span>
                        <ul>
                            <li title="Trending">{{ theme:image file="trending.png"}}<span class="left-links"><a href="">Trending</a></span></li>
                            <li title="Artist">{{ theme:image file="artist.png"}}<span class="left-links"><a href="">Artist</a></span></li>
                            <li title="Songs">{{ theme:image file="events-icon.png"}}<span class="left-links"><a href="">Genre</a></span></li>
                            <li title="Favorite">{{ theme:image file="favrate-icon.png"}}<span class="left-links"><a href="">Favorite</a></span></li>
                        </ul>
                    </div>
                    <div class="comman-box">
                        <span class="heading-comman">Friends</span>
<!--                        <ul class="friends clearfix">
                            <li><a href="#"><img src="images/profile-pictures1.jpg" /></a></li>
                            <li><a href="#"><img src="images/profile-pictures2.jpg" /></a></li>
                            <li><a href="#"><img src="images/profile-pictures3.jpg" /></a></li>
                            <li><a href="#"><img src="images/profile-pictures4.jpg" /></a></li>
                            <li><a href="#"><img src="images/profile-pictures5.jpg" /></a></li>
                            <li><a href="#"><img src="images/profile-pictures6.jpg" /></a></li>
                            <li><a href="#"><img src="images/profile-pictures7.jpg" /></a></li>
                            <li><a href="#"><img src="images/profile-pictures8.jpg" /></a></li>
                            <li><a href="#"><img src="images/profile-pictures9.jpg" /></a></li>
                        </ul>-->
                    </div>
                </div>
                <!--End left-body-container--> 
                <!--Start center-body-container-->
                <div class="center-bodyinnre-container">
                    <div class="comman-box clearfix wall-main">
                        <?php echo $content;?>
                    </div>
                </div>
                <!--End center-body-container--> 
                <!--Start right-body-container-->
                <div class="right-bodyinnre-container">
                    <div class="comman-box">
                        <span class="heading-comman">Discover Interest</span>
<!--                        <ul class="friends clearfix">
                            <li><a href="#"><img src="images/profile-pictures1.jpg" /></a></li>
                            <li><a href="#"><img src="images/profile-pictures2.jpg" /></a></li>
                            <li><a href="#"><img src="images/profile-pictures3.jpg" /></a></li>
                            <li><a href="#"><img src="images/profile-pictures4.jpg" /></a></li>
                            <li><a href="#"><img src="images/profile-pictures5.jpg" /></a></li>
                        </ul>-->
                    </div>
                    <div class="comman-box">
                        <span class="heading-comman">Friends Events</span>
<!--                        <ul class="friends clearfix">
                            <li><a href="#"><img src="images/profile-pictures1.jpg" /></a></li>
                            <li><a href="#"><img src="images/profile-pictures2.jpg" /></a></li>
                            <li><a href="#"><img src="images/profile-pictures3.jpg" /></a></li>
                            <li><a href="#"><img src="images/profile-pictures4.jpg" /></a></li>
                            <li><a href="#"><img src="images/profile-pictures5.jpg" /></a></li>
                            <li><a href="#"><img src="images/profile-pictures6.jpg" /></a></li>
                            <li><a href="#"><img src="images/profile-pictures7.jpg" /></a></li>
                            <li><a href="#"><img src="images/profile-pictures8.jpg" /></a></li>
                        </ul>-->
                    </div>
                    <div class="activity-feeds"> <span class="heading">Stream</span>
<!--                        <div class="search-box">
                            <label for="SearchBox"></label>
                            <input type="text" name="textfield" id="SearchBox">
                                <button type="submit">Go</button>
                        </div>
                        <ul class="stream">
                            <li>
                                <h2>Event Name</h2>
                                <span class="image">
                                    <img src="images/events-picture1.jpg" />
                                    <div class="display-none hover-aera"><a href="" class="float-left star-aera">Star</a>  <button type="button" class="float-right">follow</button></div>
                                </span> 
                                <span class="name"><a href="" class="float-left">512 Stats</a>  <a href="" class="float-right">1.1k Followers</a></span> 
                            </li>
                            <li>
                                <h2>Event Name</h2>
                                <span class="image">
                                    <img src="images/events-picture2.jpg" />
                                    <div class="display-none hover-aera"><a href="" class="float-left star-aera">Star</a>  <button type="button" class="float-right">follow</button></div>
                                </span> 
                                <span class="name"><a href="" class="float-left">512 Stats</a>  <a href="" class="float-right">1.1k Followers</a></span> 
                            </li>
                            <li>
                                <h2>Event Name</h2>
                                <span class="image">
                                    <img src="images/events-picture4.jpg" />
                                    <div class="display-none hover-aera"><a href="" class="float-left star-aera">Star</a>  <button type="button" class="float-right">follow</button></div>
                                </span> 
                                <span class="name"><a href="" class="float-left">512 Stats</a>  <a href="" class="float-right">1.1k Followers</a></span> 
                            </li>
                            <li>
                                <h2>Event Name</h2>
                                <span class="image">
                                    <img src="images/events-picture3.jpg" />
                                    <div class="display-none hover-aera"><a href="" class="float-left star-aera">Star</a>  <button type="button" class="float-right">follow</button></div>
                                </span> 
                                <span class="name"><a href="" class="float-left">512 Stats</a>  <a href="" class="float-right">1.1k Followers</a></span> 
                            </li>
                            <li>
                                <h2>Event Name</h2>
                                <span class="image">
                                    <img src="images/events-picture5.jpg" />
                                    <div class="display-none hover-aera"><a href="" class="float-left star-aera">Star</a>  <button type="button" class="float-right">follow</button></div>
                                </span> 
                                <span class="name"><a href="" class="float-left">512 Stats</a>  <a href="" class="float-right">1.1k Followers</a></span> 
                            </li>
                            <li>
                                <h2>Event Name</h2>
                                <span class="image">
                                    <img src="images/events-picture6.jpg" />
                                    <div class="display-none hover-aera"><a href="" class="float-left star-aera">Star</a>  <button type="button" class="float-right">follow</button></div>
                                </span> 
                                <span class="name"><a href="" class="float-left">512 Stats</a>  <a href="" class="float-right">1.1k Followers</a></span> 
                            </li>

                        </ul>
                        <hr />
                        <span class="more">See more suggestion</span>-->
                    </div>
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
