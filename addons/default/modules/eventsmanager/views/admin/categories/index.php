<section class="title">
    <h4>Categories and Sub Categories</h4>
</section>

<section class="item">
    <div class="content">

        <?php if ( $categories ): ?>

            <?php echo form_open('admin/eventsmanager/categories/delete') ?>

            <table border="0" class="table-list" cellspacing="0">
                <thead>
                    <tr>
                        <!--<th width="20"><?php echo form_checkbox(array( 'name' => 'action_to_all', 'class' => 'check-all' )) ?></th>-->
                        <th width="150">Category Title</th>
                        <th width ="150"><?php echo lang('global:slug') ?></th>
                        <th width="450">Description</th>
                        <th>Parent Category</th>
                        <th>Events Associated</th>
                        <th width="120"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $categories as $category ): ?>
                        <tr>
                            <!--<td><?php //echo form_checkbox('action_to[]', $category['id']) ?></td>-->
                            <td><?php echo $category['title'] ?></td>
                            <td><?php echo $category['slug'] ?></td>
                            <td><?php echo $category['description'] ?></td>
                            <td><?php echo !empty($category['category_title']) ? $category['category_title'] : '-' ?></td>
                            <td><?php echo ($category['event_count']==0) ? '-' : $category['event_count'];?></td>
                            <td class="align-center buttons buttons-small">
                                <?php echo anchor('admin/eventsmanager/categories/edit/' . $category['id'], lang('global:edit'), 'class="button edit"') ?>
                                <?php if(!$this->event_categories_m->is_category_associated($category['id'])):?>
                                <?php echo anchor('admin/eventsmanager/categories/delete/' . $category['id'], lang('global:delete'), 'class="confirm button delete"') ; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>

            <?php $this->load->view('admin/partials/pagination') ?>

<!--            <div class="table_action_buttons">
                <?php //$this->load->view('admin/partials/buttons', array( 'buttons' => array( 'delete' ) )) ?>
            </div>-->

            <?php echo form_close() ?>

        <?php else: ?>
            <div class="no_data"><?php echo lang('sub_cat:no_sub_categories') ?></div>
        <?php endif ?>
    </div>
</section>