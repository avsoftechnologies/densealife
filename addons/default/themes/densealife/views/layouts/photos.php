<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        {{ theme:partial name='metadata' }}
    </head>

    <body>
        {{ integration:analytics }}
        <?php $module_path = BASE_URL . $this->module_details['path']; ?>
        <div id="wepper-inner">
            <header id="wepper">
               {{ theme:partial name='head' }}
            </header>
            <div id="body-container-inner" class="clearfix"> 
  	<div class="links-header clearfix">
        <ul class="comman-links">
            <li><a href="">About</a></li>
            <li><a href="">Photos</a></li>
            <li><a href="">Events</a></li>
            <li><a href="">Interest</a></li>
            <li><a href="">Frieds</a></li>
        </ul>
      </div>
    <!--Start left-body-container-->
    <div class="left-bodyinnre-container">
		<span class="clear user-name">User Name</span>
        <span class="user-picutre clearfix"><img src="" /></span>
        <span class="follower-right"><a href="#">455 Followers</a></span>
    	<div class="comman-box">
        	<span class="heading-comman">About Me</span>
            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. </p>
        </div>
        <div class="clearfix"><button class="btn-color common">Follow</button><button class="common">Add Friends</button></div>
    	<div class="comman-box">
        	{{theme:partial name='blocks/social_profiles'}}
        </div>
        <div class="comman-box">
        	{{theme:partial name='blocks/friends'}}
        </div>
    </div>
    <!--End left-body-container--> 
    <!--Start center-body-container-->
    <div class="center-bodyinnre-container">
    	<div class="comman-box">
        	 <ul class="videos clearfix">
             	<li><img src="" /><span class="name">Ablums</span></li>
                <li><img src="" /><span class="name">Events</span></li>
	            <li><img src="" /><span class="name">Photos</span></li>
                <li><img src="" /><span class="name">Music</span></li>
            </ul>
        </div>
        <div class="comman-box clearfix">
                <ul class="search-box clearfix">
                <li><button>Following (179)</button></li>
                <li><button>Friends</button></li>
                <li><button>Message</button></li>
            </ul>
        </div>
    	<div class="comman-box clearfix">
			<div class="comman-heading">Photos</div>
            <ul class="search-box clearfix">
                <li class="right"><button>Add Album</button></li>
                <li class="right"><button class="btn-color">Add Photo/ Video</button></li>
            </ul>
            <ul class="videos">
            	<li><img src="" /><span class="name">Album 1 (32 Photos)</span></li>
                <li><img src="" /><span class="name">Album 1 (32 Photos)</span></li>
	            <li><img src="" /><span class="name">Album 1 (32 Photos)</span></li>
                <li><img src="" /><span class="name">Album 1 (32 Photos)</span></li>
                <li><img src="" /><span class="name">Album 1 (32 Photos)</span></li>
                <li><img src="" /><span class="name">Album 1 (32 Photos)</span></li>
                <li><img src="" /><span class="name">Album 1 (32 Photos)</span></li>
                <li><img src="" /><span class="name">Album 1 (32 Photos)</span></li>
                <li><img src="" /><span class="name">Album 1 (32 Photos)</span></li>
                <li><img src="" /><span class="name">Album 1 (32 Photos)</span></li>
                <li><img src="" /><span class="name">Album 1 (32 Photos)</span></li>
                <li><img src="" /><span class="name">Album 1 (32 Photos)</span></li>
            </ul>
        </div>
    </div>
    <!--End center-body-container--> 
    <!--Start right-body-container-->
    <div class="right-bodyinnre-container">
    	<div class="comman-box">
        	<span class="heading-comman">Information</span>
            <p>From: Province/ State, Country</p>
            <p>Works: Employer Name, 2014</p>
            <p>School: School Name, 2014</p>
            <p>RekationShip: Unmarrid</p>
            <p>Born: June, 1st 2014</p>
        </div>
        <div class="comman-box">
        	{{theme:partial name="blocks/event_interests"}}
        </div>
        <div class="comman-box">
        	{{theme:partial name="blocks/event_favorites"}}
        </div>
        <div class="comman-box">
        	<span class="heading-comman">Events</span><a href="#">(62)</a>
            <ul class="modify-block clearfix">
              <li><span>Event Name</span><a href="#"><img src="images/profile-pictures1.jpg" /></a></li>
              <li><span>Event Name</span><a href="#"><img src="images/profile-pictures2.jpg" /></a></li>
              <li><span>Event Name</span><a href="#"><img src="images/profile-pictures3.jpg" /></a></li>
              <li><span>Event Name</span><a href="#"><img src="images/profile-pictures4.jpg" /></a></li>
            </ul>
        </div>
    </div>
    <!--End Right-body-container--> 
  </div>
            <footer>
                {{ theme:partial name="footer" }}   
            </footer>
        </div>
    </body>
</html>
