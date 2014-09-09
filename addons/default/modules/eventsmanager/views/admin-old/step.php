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
        
         <?php echo $content; ?>

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