<section class="title">
	<?php if ($this->controller == 'admin_categories' && $this->method === 'edit'): ?>
	<h4><?php echo sprintf(lang('cat:edit_title'), $category->title);?></h4>
	<?php else: ?>
	<h4><?php echo lang('cat:create_title');?></h4>
	<?php endif ?>
</section>

<section class="item">
<div class="content">
<?php echo form_open($this->uri->uri_string(), 'class="crud'.((isset($mode)) ? ' '.$mode : '').'" id="categories"') ?>
<input type="hidden" id="author" name="author" value="<?php echo isset($category->author) ? $category->author : $this->current_user->id ; ?>" />
<div class="form_inputs">

	<ul>
		<li class="even">
			<label for="title"><?php echo lang('global:title');?> <span>*</span></label>
			<div class="input"><?php echo  form_input('title', $category->title) ?></div>
        </li>
        <li class="">
			<label for="slug"><?php echo lang('global:slug') ?> <span>*</span></label>
			<div class="input"><?php echo  form_input('slug', $category->slug) ?></div>
			<?php echo  form_hidden('id', $category->id) ?>
		</li>
        <li>
            <label for="parent_id">Parent Category</label>
            <div class="input">
            <?php echo form_dropdown('parent_id', array(''=>lang('cat:no_category_select_label')) + $parent_categories, $category->parent_id) ?>
            </div>
        </li>
        
        <?php if(empty($is_ajax_request)) : ?>
        <li class="editor even">
            <label for="description"><?php echo lang('eventsmanager:description_label'); ?></label>
            <div class="input"><?php echo form_textarea(array('id'=>'description', 'name'=>'description', 'value' => isset($category->description) ? $category->description : '', 'rows' => 10)); ?></div>
        </li>
        <?php endif ;?>
	</ul>

</div>

	<div><?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )) ?></div>

<?php echo form_close() ?>
</div>
</section>