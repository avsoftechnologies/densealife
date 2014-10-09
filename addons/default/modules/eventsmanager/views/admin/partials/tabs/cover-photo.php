
<?php if ($this->method == 'manage'): ?>
    <style>
        .cp-wrap {
            width: 1013px;
            height: 300px;
            overflow: hidden;
            margin: auto;
            cursor: -webkit-grab;

        }
    </style>
    <script type="text/javascript" src="<?php echo base_url('assets/jdrag/js/jquery.imagedrag.js'); ?>"></script>
    <script type="text/javascript">
        $(function() {
            $('.cp-wrap').imagedrag({
                input: "#output",
                position: 'middle',
                attribute: "value",
                pixel: '<?php echo $event->cover_photo_pos; ?>'
            });
            
            $('.btn-save-cp').click(function(e){
                $.post('/eventsmanager/save_cp_pos',
                     {
                        new_cp_pos: $('#output').val(),
                        event_id:$('#cp_event_id').val()
                    },
                    function(res){
                        if(res.status==='success'){
                            $('.response-txt').text('Cover photo saved successfully...').hide(10000);
                        }
                    },
                    'json'
                );
                e.preventDefault(); 
            }); 
        });
        
    </script>
    <fieldset>        
        <div class="cp_save_button">

            <form method="post" id="save_cp_pos">
                <!--<input type="hidden" name="prev_cp_pos" id="cp_pos" value="{{event:cover_photo_pos}}px"/>-->
                <input type="text" name="new_cp_pos" id="output" value="<?php echo $event->cover_photo_pos; ?>px"/>
                <input type="text" name="event_id" id="cp_event_id" value="<?php echo $event->id; ?>"/>
            </form>                  
        </div>
        <div class="cp-wrap">
            <img src="/files/large/<?php echo $event->cover_photo; ?>" style="width:1024px; height:768px; position:relative; top:<?php echo $event->cover_photo_pos; ?>px"/>
        </div>
        <div class="response-txt"></div>
        <div style="float:right;margin-right:105px;">
            <button class="btn blue btn-save-cp" value="save" name="btnAction">
                <span>Save Coverphoto</span>
            </button>
        </div>
        <div class="coverphoto"></div>
        <input type="hidden" name="cover_photo" id="cover_photo" value="<?php echo isset($event->cover_photo) ? $event->cover_photo : null; ?>" />
        <br class="clear-both" />    
    </fieldset>
    <?php
 endif;