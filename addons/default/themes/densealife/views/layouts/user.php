<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        {{ theme:partial name='metadata' }}
        <script type="text/javascript" src="/js/album.js"></script>
    </head>

    <body>
        {{ integration:analytics }}
        <script>
            var username = "{{_user:username}}";
        </script>
        <?php $module_path = BASE_URL . $this->module_details['path']; ?>
        <div id="wepper-inner">
            <header id="wepper">
                {{ theme:partial name='head' }}
            </header>
            <div id="body-container-inner" class="clearfix"> 
                <div class="links-header clearfix">
                    <ul class="comman-links">
                        <li data-user="{{_user:username}}" class="user-page-links user-page-about<?php echo (($this->router->fetch_method()=='about' or $this->router->fetch_method()=='view') ? ' active ' : '');?>"><a href="#about">About</a></li>
                        <li data-user="{{_user:username}}" class="user-page-links user-page-photos<?php echo (($this->router->fetch_method()=='photos') ? ' active ' : '');?>"><a href="#photos">Photos</a></li>
                        <li data-user="{{_user:username}}" class="user-page-links user-page-events<?php echo (($this->router->fetch_method()=='events') ? ' active ' : '');?>"><a href="#events">Events</a></li>
                        <li data-user="{{_user:username}}" class="user-page-links user-page-interests<?php echo (($this->router->fetch_method()=='interests') ? ' active ' : '');?>"><a href="#interests">Interests</a></li>
                        <li data-user="{{_user:username}}" class="user-page-links user-page-friends<?php echo (($this->router->fetch_method()=='friends') ? ' active ' : '');?>"><a href="#friends">Friends</a></li>
                    </ul>
                </div>
                <div class="left-bodyinnre-container">
                    <span class="clear user-name">{{_user:display_name}}</span>
                    <span class="user-picutre clearfix">{{user:profile_pic_180 user_id=_user:id}}</span>
                    <span class="clear"></span>
                    <?php if($_user->id==$this->current_user->id):?>
                        <span>
                            <a class="fancybox fancybox.ajax" href="/edit-profile">Edit Profile</a>
                        </span>
                    <?php endif;?>
                    <span class="follower-right"><a href="#">{{profile:count_followers user_id =_user:id}} Follower(s)</a></span>
                    <div class="comman-box">
                        <span class="heading-comman">About Me</span>
                        <p>{{_user:bio}}</p>
                    </div>
                    {{ if user:logged_in_user !== _user:id}}
                     <div class="clearfix">
                         {{button:follow user_id=_user:id}}
                         {{button:friend user_id=_user:id}}
                     </div>
                     {{endif}}
                    
                    <div class="comman-box">
                        {{theme:partial name='blocks/social_profiles'}}
                    </div>
                     <div class="comman-box">
                        {{profile:block_friends user_id=_user:id}}
                     </div>
                </div>
                
                <div class="center-bodyinnre-container">
                    {{theme:partial name='content'}}
                </div>
                <div class="right-bodyinnre-container">
                    <div class="comman-box">
                        <span class="heading-comman">Information</span>
                        {{user:profile user_id=_user:id}}
                        <p>From: {{address_line1}},{{address_line2}}, {{address_line3}}</p>
<!--                        <p>Works: Employer Name, 2014</p>
                        <p>School: School Name, 2014</p>
                        <p>RelationShip: Unmarrid</p>-->
                        <p>Born: {{ helper:date format="F,dS Y" timestamp=dob }}</p>
                        {{/user:profile}}
                    </div>
                    {{profile:right_side_bar_blocks user_id=_user:id type='event'}}
                    {{profile:right_side_bar_blocks user_id=_user:id type='interest'}}
                    {{profile:right_side_bar_blocks user_id=_user:id type='favorite'}}
                </div>
            </div>
            <footer>
                {{ theme:partial name="footer" }}   
            </footer>
        </div>
    </body>
</html>
