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
                <div class="links-header txt-center">
                    <ul class="comman-links link-header-event <?php echo (!isset($type) || $type == 'event') ? 'd-block' : 'd-none'; ?>">
                        <li class="<?php echo (($this->router->fetch_method()=='events') ? ' active ' : '');?>page-events" data-page="events"><a href="#events">Events</a><?php //echo anchor('' , 'Events') ; ?></li>
                        <li class="<?php echo (($this->router->fetch_method()=='trending') ? ' active ' : '');?>page-trending" data-page="trending"><a href="#trending">Trending</a><?php //echo anchor('profile/page/upcoming' , 'Upcoming') ; ?></li>
                        <li class="<?php echo (($this->router->fetch_method()=='upcoming') ? ' active ' : '');?>page-upcoming" data-page="upcoming"><a href="#upcoming">Upcoming</a><?php //echo anchor('profile/page/upcoming' , 'Upcoming') ; ?></li>
                        <li class="<?php echo (($this->router->fetch_method()=='favorites') ? 'active ' : '');?>page-favorite" data-page="favorite"><a href="#favorite">Favorites</a><?php //echo anchor('profile/page/favorites' , 'Favorites') ; ?></li>
                    </ul>
                    <ul class="comman-links <?php echo (isset($type) && $type == 'interest') ? 'd-block' : 'd-none'; ?> link-header-interest">
                        <li class="<?php echo (($this->router->fetch_method()=='interests') ? ' active ' : '');?>"><a href="#i-interests">Interests</a></li>
                        <li class="<?php echo (($this->router->fetch_method()=='trending') ? ' active ' : '');?>"><a href="#i-trending">Trending</a></li>
                        <li class="<?php echo (($this->router->fetch_method()=='popular') ? ' active ' : '');?>"><a href="#i-popular">Populars</a></li>
                        <li class="<?php echo (($this->router->fetch_method()=='favorites') ? ' active ' : '');?>"><a href="#i-favorite">Favorites</a></li>
                       
                    </ul>
                </div>
                <!--Start left-body-container-->
                <div class="left-bodyinnre-container">
                    <div class="logo-densea-life-inner">{{user:profile_pic user_id="<?php echo $user->id;?>" dim="70"}}</div>
                    <!--<div class="logo-densea-life-inner">{{ theme:image file="logo.png"}}</div>-->
                    <span class="user-profile"><?php echo $user->display_name;?><?php if($user->id==$this->current_user->id):?><a class="fancybox fancybox.ajax" href="/edit-profile">Edit</a><?php endif;?></span>
                    <div class="comman-box">
                        {{ theme:partial name="blocks/information" user_id="<?php echo $user->id;?>" }}
                    </div>
                    <div class="comman-box">
                        {{ theme:partial name="blocks/events" }}
                    </div>
                    
                     <div class="comman-box">
                        {{ theme:partial name="blocks/interests" }}
                        
                    </div>
                    <?php if(!empty($friends)):?>
                    <div class="comman-box">
                        <span class="heading-comman">Friends</span>
                        <ul class="friends clearfix">
                            <?php foreach($friends as $friend):?>
                            <li>
                                <a href="/user/<?php echo $friend->username;?>" title='<?php echo $friend->display_name;?>'>
                                    {{user:profile_pic user_id='<?php echo $friend->user_id;?>'}}
                                </a>
                            </li>
                            <?php endforeach;?>
                        </ul>
                    </div>
                    <?php endif;?>
                </div>
                <!--End left-body-container--> 
                <!--Start center-body-container-->
                <div class="center-bodyinnre-container">
                    <div class="comman-box clearfix wall-main">
                        {{theme:partial name='content'}}
                    </div>
                </div>
                <!--End center-body-container--> 
                <!--Start right-body-container-->
                <div class="right-bodyinnre-container">
                    <div class="comman-box">
                        <span class="heading-comman">Discover Interest</span>
                        <ul class="friends clearfix">
                            {{ eventsmanager:trending limit="8" type='interest' user_id="<?php echo $user->id;?>"}}
                            <li>
                                <a href="{{ url:site uri='eventsmanager/{{slug}}' }}" title="{{ title }}" >{{ eventsmanager:thumb name="{{ thumbnail }}" }}</a>
                            </li>
                            {{ /eventsmanager:trending }}
                        </ul>
                    </div>
                    <div class="comman-box">
                        <span class="heading-comman">Friends Events</span>
                        <ul class="friends clearfix">
                            {{friend:events user_id="<?php echo $user->id;?>"}}
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
