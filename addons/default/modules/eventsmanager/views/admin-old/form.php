<?php
$module_path = BASE_URL . $this->module_details['path'] ;
?>

<section class="title">
    <?php if ( $this->method == 'create' ): ?>
        <h4><?php echo lang('eventsmanager:new_event_label') ; ?></h4>
    <?php else: ?>
        <h4><?php echo lang('eventsmanager:manage_event_label') . ' : ' . $event->title ; ?></h4>
    <?php endif ; ?>
</section>

<section class="item">
    <div class="content">

        <?php echo form_open_multipart() ; ?>

        <div class="tabs">

            <ul class="tab-menu">
                <li><a class="tab" href="#content-tab"><span><?php echo lang('eventsmanager:content_label') ; ?></span></a></li>
                <li><a class="tab" href="#map-tab"><span><?php echo lang('eventsmanager:map_label') ; ?></span></a></li>
                <?php if ($this->method == 'manage' ): ?>
                <li><a class="tab" href="#album-tab"><span>Album</span></a></li>
                <li><a class="tab" href="#cover-photo"><span>Cover Photo</span></a></li>
                <li><a class="tab" href="#picture-tab"><span><?php echo lang('eventsmanager:thumbnail_label') ; ?></span></a></li>
                <?php endif; ?>
            </ul>

            <!-- Content tab -->
            <div class="form_inputs" id="content-tab">
                <?php $this->load->view('/admin/partials/tabs/main',array(
                        'event'         => $event,
                        'categories'    => $categories,
                        'sub_categories'=> $sub_categories,
                        'date_format'   => $date_format,
                    ));
                ?>
            </div>
            <!-- Map tab -->
            <div class="form_inputs" id="map-tab">
                 <?php 
                    $this->load->view('/admin/partials/tabs/map', array(
                        'event'         =>  $event,
                    )); 
                ?>
            </div>
            <?php if ( $this->method != 'create' ):?>
             <div id="album-tab">
                <?php 
                    $this->load->view('/admin/partials/tabs/album',array(
                        'event' => $event,
                        'folders'       =>  $folders,
                        'pictures'      =>  $pictures,
                        'module_path'   =>  $module_path
                    ));
                ?>
            </div>
             <!-- Picture tab -->
            
            <div id="picture-tab">
                <?php 
                    $this->load->view('/admin/partials/tabs/thumbnail', array(
                        'event'         =>  $event,
                        'folders'       =>  $folders,
                        'pictures'      =>  $pictures,
                        'module_path'   =>  $module_path
                    )); 
                ?>
            </div>
            <div id="cover-photo">
                <?php 
                    $this->load->view('/admin/partials/tabs/cover-photo',array(
                        'event' => $event,
                    ));
                ?>
            </div>
             
           
            <?php endif; ?>

        </div>

        <div class="buttons align-right padding-top">
            <?php 
                $this->load->view('admin/partials/buttons', array( 
                        'buttons' => array( 
                                'save', 
                                'save_exit', 
                                'cancel' 
                            ),
                        )
                ) ; 
            ?>
        </div>

<?php echo form_close() ; ?>

    </div>
</section>

<script type="text/javascript">
// PARAMS
    var CURRENT_LANGUAGE = '<?php echo CURRENT_LANGUAGE ; ?>';
    var JS_DATE_FORMAT = '<?php echo addslashes(php2js_date_format($date_format)) ; ?>';
    var DATE_INTERVAL = <?php echo $date_interval ; ?>;
// LANG
    var LANG_NO_IMAGE = '<?php echo lang('eventsmanager:no_image_in_folder') ; ?>';
    var LANG_AJAX_ERROR = '<?php echo lang('eventsmanager:ajax_error') ; ?>';
    var MAP_PLACE = '<?php echo isset($event->place) ? $event->place : "" ; ?>';
</script>