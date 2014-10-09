<?php $module_path = BASE_URL . $this->module_details['path'] ; ?>

<section class="title">
    <h4>List: Events awaiting approval to be published</h4>
</section>

<section class="item">
    <div class="content">
        <?php
        if ( count($unpub_events) > 0 ):
            echo form_open('admin/eventsmanager/delete') ;
            ?>

            <table>

                <thead>
                    <tr>
                        <th class="checkbox"><?php echo form_checkbox(array( 'name' => 'action_to_all', 'class' => 'check-all' )) ; ?></th>
                        <th class="event-picture"><?php echo lang('eventsmanager:thumbnail_label') ; ?></th>
                        <th class="event-content"><?php echo lang('eventsmanager:event_label') ; ?></th>
                        <th class="event-date"><?php echo lang('eventsmanager:date_label') ; ?></th>
                        <th class="align-center buttons"><?php echo lang('eventsmanager:actions_label') ; ?></th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ( $unpub_events as $event ) { ?>
                        <tr>
                            <td class="checkbox"><?php echo form_checkbox('action_to[]', $event->id) ; ?></td>
                            <td class="event-picture">
                                <?php
                                if ( is_file(UPLOAD_PATH . 'files/' . $event->thumbnail) ) :
                                    echo img(array( 'src' => UPLOAD_PATH . 'files/' . $event->thumbnail . '?' . mt_rand(0, 10000), 'width' => 100, 'height' => 100 )) ;
                                elseif ( isset($event->picture_id) ) :
                                    echo img(array( 'src' => 'files/thumb/' . $event->picture_id.'/100' )) ;
                                else :
                                    echo img(array( 'src' => $module_path . '/img/event.png', 'style' => 'height: 100px ; width:100px;' )) ;
                                endif ;
                                ?>
                            </td>
                            <td class="event-content"><h6><b><?php echo $event->title ?></b></h6><?php echo textSumUp(strip_tags($event->description), 250) ; ?></td>
                            <td class="event-date"><?php echo format_date($event->start_date) ; ?></td>
                            <td>
                                <?php echo anchor('admin/eventsmanager/manage/' . $event->id, lang('eventsmanager:manage_label'), 'class="btn orange edit"') ; ?>
                                <?php echo anchor('admin/eventsmanager/publish/' . $event->id, 'Publish', array( 'class' => 'btn blue' )) ; ?>
                                <?php echo anchor('admin/eventsmanager/delete/' . $event->id, lang('global:delete'), array( 'class' => 'confirm btn red delete' )) ; ?>
                            </td>
                        </tr>
                <?php } ?>
                </tbody>

            </table>

            <div class="buttons align-right padding-top">
            <?php $this->load->view('admin/partials/buttons', array( 'buttons' => array( 'publish' ) )) ; ?>
            <?php $this->load->view('admin/partials/buttons', array( 'buttons' => array( 'delete' ) )) ; ?>
            </div>

            <?php
            echo form_close() ;
        else:
            ?>
            <div class="blank-slate">
               There are no any event to be approved !!!
            </div>
<?php endif ; ?>
    </div>
</section>