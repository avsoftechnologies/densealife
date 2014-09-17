<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        {{ theme:partial name='metadata' }}
        <?php if ($this->current_user->id == $event->author): ?>
            <script type="text/javascript" src="<?php echo base_url('assets/jdrag/js/jquery.imagedrag.js'); ?>"></script>
            <script type="text/javascript">
    $(function() {

    $('.cp-wrap').imagedrag({
        input: "#output",
        position: 'middle',
        attribute: "value",
        pixel: '{{event:cover_photo_pos}}'
    });
    $('#save_cp_pos').ajaxForm({
        success: function(response) {

        }
    });
    });
            </script>
        <?php endif; ?>
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
                <div class="logo-densea-life">   
                    <?php
                    if (is_file(UPLOAD_PATH . 'files/' . $event->thumbnail)) :
                        echo img(array('src' => UPLOAD_PATH . 'files/' . $event->thumbnail, 'height' => 150, 'width' => 150));
                    elseif (isset($event->picture_id)) :
                        echo img(array('src' => 'files/thumb/' . $event->picture_id . '/150'));
                    else :
                        echo img(array('src' => $module_path . '/img/event.png'));
                    endif;
                    ?>   

                </div>
                <?php if ($this->current_user->id == $event->author): ?>
                    <div class="cp_save_button">

                        <form method="post" action="/eventsmanager/save_cp_pos" id="save_cp_pos">
                            <!--<input type="hidden" name="prev_cp_pos" id="cp_pos" value="{{event:cover_photo_pos}}px"/>-->
                            <input type="hidden" name="new_cp_pos" id="output" value="{{event:cover_photo_pos}}px"/>
                            <input type="hidden" name="event_id" value="{{event:id}}"/>
                            <button style="width: 116px;">Save Position</button>
                        </form>                  
                    </div>
                <?php endif; ?>

                <div class="banner-header">
                    <div class="cp-wrap">
                        <img src="/files/large/{{event:cover_photo}}" style="width:1024px; height:768px; position:relative; top:{{event:cover_photo_pos}}px"/>
                    </div>

                </div>
            </header>
            <!--End Header--> 
            <!--Start Body Container-->
            <div id="body-container-inner" class="clearfix"> 
                <div class="links-header">
                    <ul class="txt-center comman-links event-links">
                        <li class="<?php echo (($this->router->fetch_method() == 'wall') ? 'active ' : ''); ?>page-index" data-slug="<?php echo $event->slug; ?>"><a href="#wall">Activity</a></li>
                        <li class="<?php echo (($this->router->fetch_method() == 'about') ? 'active ' : ''); ?>page-about" data-slug="<?php echo $event->slug; ?>"><a href="#about">About</a></li>
                        <li class="<?php echo (($this->router->fetch_method() == 'albums') ? 'active' : ''); ?>page-albums" data-slug="<?php echo $event->slug; ?>"><a href="#albums">Albums</a></li>
                        <li class="<?php echo (($this->router->fetch_method() == 'videos') ? 'active' : ''); ?>page-videos" data-slug="<?php echo $event->slug; ?>"><a href="#videos">Videos</a></li>
                        <li class="<?php echo (($this->router->fetch_method() == 'followers') ? 'active' : ''); ?>page-followers" data-slug="<?php echo $event->slug; ?>"><a href="#followers">Followers</a></li>
                    </ul>
                </div>
                <!--Start left-body-container-->
                <div class="left-bodyinnre-container">
                    <span class="follower-right f-bold fs14"><?php echo anchor('eventsmanager/wall/' . $event->slug, $event->title); ?></span>
                    <?php if ($event->author == $this->current_user->id): ?>
                        <span class="follower-right"><?php echo anchor('eventsmanager/edit/' . $event->slug, 'Edit'); ?></span>
                    <?php endif; ?>
                    <div class="comman-box">
                        <span class="heading-comman">Information</span>
                        <p><?php echo $event->about ? $event->about : ''; ?></p>
                    </div>
                    {{button:follow_event event_id='<?php echo $event->id; ?>' reload='true'}}
                    {{button:favorite_event event_id='<?php echo $event->id; ?>'}}
                    <div>
                        <span>
                            {{button:star_event event_id='<?php echo $event->id; ?>'}}
                        </span>
                        <span>
                            <span class="d-inline count_star_<?php echo $event->id; ?>"><?php echo $event->star_count; ?></span>  &nbsp;Stars</a> 
                        </span>
                    </div>
                    <span class="location">Location : <?php echo $event->place; ?></span>
                    <span class="location">
                        Date: 
                        <?php
                        if ($event->end_date_defined && isset($event->end_date)):
                            if (date('Y-m-d', strtotime($event->start_date)) != date('Y-m-d', strtotime($event->end_date))):

                                echo lang('eventsmanager:from_date_label') . '&nbsp' . format_date($event->start_date) . '&nbsp' .
                                lang('eventsmanager:at_time_label') . '&nbsp' . $event->start_time . '&nbsp - &nbsp' .
                                lang('eventsmanager:to_date_label') . '&nbsp' . format_date($event->end_date) . '&nbsp' .
                                lang('eventsmanager:at_time_label') . '&nbsp' . $event->end_time;
                            else:

                                echo lang('eventsmanager:on_date_label') . '&nbsp' . format_date($event->start_date) . '&nbsp' .
                                lang('eventsmanager:from_time_label') . '&nbsp' . $event->start_time . '&nbsp' .
                                lang('eventsmanager:to_time_label') . '&nbsp' . $event->end_time;
                            endif;

                        else:

                            echo lang('eventsmanager:on_date_label') . '&nbsp' . format_date($event->start_date) . '&nbsp' .
                            lang('eventsmanager:at_time_label') . '&nbsp' . $event->start_time;
                        endif;
                        ?>
                    </span>
                </div>
                <div class="center-bodyinnre-container">
                    <div class="comman-box clearfix wall">
                        {{ theme:partial name="content" }}
                    </div>
                </div>
                <div class="right-bodyinnre-container">
                    <span class="f-bold follower-right">
                        {{trends:followers_count entry_id='{{event:id}}'}} Followers
                    </span>
                    <script>
                        $(function() {
                            $('.add_friend').click(function() {
                                $.fancybox({
                                    // fallback size
                                    fitToView: false, // set the specific size without scaling to the view port
                                    autoSize: false,
                                    'href': '/eventsmanager/add_friend/{{event:slug}}',
                                    'type': 'iframe'
                                });

                                return false;
                            });
                            $('.invite_by_mail').click(function() {
                                $.fancybox({
                                    width: 500,
                                    minHeight: 200,
                                    'href': '/eventsmanager/invite_by_mail/{{event:slug}}',
                                    'type': 'iframe'
                                });
                                return false;
                            });
                        });
                    </script>

                    <button class="btn-color common add_friend">Add Friend</button>
                    <button class="common invite_by_mail">Invite by Mail</button>
                    {{eventsmanager:event_follower_friends entry_id=event:id limit='6'}}
                    <div class="fl activity-feeds"> 
                        {{friend:list_friends}}
                        <span class="heading">Friends Suggestions</span>
                        <ul class="friend_suggestions">
                            {{friend:list_friends}}
                            <li class="li_{{user_id}} mt10">
                                <div class="fl">
                                    {{user:profile_pic user_id='{{user_id}}'}}
                                </div>
                                <div>
                                    <span>
                                        <a class="color-blue fb fs14 capitalize" href='/densealife-page/{{username}}'>{{display_name}}</a>
                                    </span>
                                    <br/>
                                    <span class="fs10">
                                        {{button:invite_friend eid=event:id fid="{{user_id}}"}}
                                    </span>
                                </div> 
                            </li>
                            {{/friend:list_friends}}
                        </ul>
                        <hr />
                        <span class="more">See more suggestion</span>
                    </div>
                </div>
            </div>
            <footer>
                {{ theme:partial name="footer" }}   
            </footer>
        </div>
    </body>
</html>
