<link rel="stylesheet" href="http://localhost/pyrocms/system/cms/themes/pyrocms/css/jquery/jquery.tagsinput.css" />
<link rel="stylesheet" href="http://localhost/pyrocms/addons/default/modules/eventsmanager/css/jquery.fileupload-ui.css" />
<link rel="stylesheet" href="http://localhost/pyrocms/addons/default/modules/eventsmanager/css/album.css" />
<script src="http://localhost/pyrocms/system/cms/themes/pyrocms/js/jquery/jquery.tagsinput.js"></script>
<script src="http://localhost/pyrocms/addons/default/modules/eventsmanager/js/jquery.fileupload.js"></script>
<script src="http://localhost/pyrocms/addons/default/modules/eventsmanager/js/jquery.fileupload-ui.js"></script>
<script src="http://localhost/pyrocms/addons/default/modules/eventsmanager/js/functions.js"></script>
<fieldset>
    <section class="title">
        <h4 id="file-title">
            <?php echo lang('albums:files_title') ?>
        </h4>

        <div id="file-toolbar">
            <div id="file-buttons">
                <ul class="button-menu-source buttons">
                    <li class="button animated fadeIn" data-applies-to="pane root-pane"	data-menu="refresh"><?php echo lang('albums:refresh') ?></li>
                    <li class="button animated fadeIn" data-applies-to="folder" data-menu="open"><?php echo lang('albums:open') ?></li>
                    <li class="button animated fadeIn" data-role="create_folder"data-applies-to="pane root-pane" data-menu="new-folder"><?php echo lang('albums:new_album') ?></li>
                    <li class="button animated fadeIn" data-role="upload" data-applies-to="folder pane" data-menu="upload"><?php echo lang('albums:upload') ?></li>
                    <li class="button animated fadeIn" data-role="edit_file" data-applies-to="file" data-menu="rename"><?php echo lang('albums:rename') ?></li>
                    <li class="button animated fadeIn" data-role="edit_folder" data-applies-to="folder" data-menu="rename"><?php echo lang('albums:rename') ?></li>
                    <li class="button animated fadeIn" data-role="download_file" data-applies-to="file" data-menu="download"><?php echo lang('albums:download') ?></li>
                    <li class="button animated fadeIn" data-role="synchronize" data-applies-to="folder" data-menu="synchronize"><?php echo lang('albums:synchronize') ?></li>
                    <li class="button animated fadeIn" data-role="upload delete_file" data-applies-to="file" data-menu="replace"><?php echo lang('albums:replace') ?></li>
                    <li class="button animated fadeIn" data-role="delete_file" data-applies-to="file" data-menu="delete"><?php echo lang('albums:delete') ?></li>
                    <li class="button animated fadeIn" data-role="delete_folder" data-applies-to="folder" data-menu="delete"><?php echo lang('albums:delete') ?></li>
                    <li class="button animated fadeIn" data-applies-to="folder file pane"	data-menu="details"><?php echo lang('albums:details') ?></li>
                </ul>
            </div>
            <input type="text" id="file-search" name="file-search" value="" placeholder="<?php echo lang('albums:search_message') ?>"/>
        </div>
    </section>

    <section class="item">
        <section class="side">
            <ul id="folders-sidebar">
                <li class="folder places" data-id="0"><a href="#"><?php echo lang('albums:places') ?></a></li>
                <?php if ( !$albums ) : ?>
                    <li class="no_data"><?php echo lang('albums:no_album_places') ?></li>
                <?php elseif ( $album_tree ) :?>
                    <?php echo tree_builder($album_tree, '<li class="folder" data-id="{{ id }}" data-name="{{ name }}" data-filename="{{ filename }}"><div></div><a href="#">{{ name }}</a>{{ children }}</li>') ?>
                <?php endif ?>
            </ul>
        </section>

        <section class="center">

            <?php if ( !$albums ) : ?>
                <div class="no_data"><?php echo lang('albums:no_albums') ?></div>
            <?php endif ?>

            <ul class="folders-center pane"></ul>

            <ul class="context-menu-source">
                <!--<li data-applies-to="file" data-role="create_thumbnail" data-menu="create-thumbnail">Create Thumbnail</li>-->
                <li data-applies-to="folder" data-menu="open"><?php echo lang('albums:open') ?></li>
                <li data-role="create_folder" data-applies-to="pane root-pane" data-menu="new-folder"><?php echo lang('albums:new_album') ?></li>
                <li data-role="upload" data-applies-to="folder pane" data-menu="upload"><?php echo lang('albums:upload') ?></li>
                <li data-role="edit_file" data-applies-to="file" data-menu="rename"><?php echo lang('albums:rename') ?></li>
                <li data-role="upload delete_file" data-applies-to="file" data-menu="replace"><?php echo lang('albums:replace') ?></li>
                <li data-role="edit_folder" data-applies-to="folder" data-menu="rename"><?php echo lang('albums:rename') ?></li>
                <!--<li data-applies-to="file" data-menu="edit"><?php echo lang('albums:edit') ?></li>-->
                <li data-role="download_file" data-applies-to="file" data-menu="download"><?php echo lang('albums:download') ?></li>
                <li data-role="synchronize" data-applies-to="folder" data-menu="synchronize"><?php echo lang('albums:synchronize') ?></li>
                <li data-role="delete_file" data-applies-to="file" data-menu="delete"><?php echo lang('albums:delete') ?></li>
                <li data-role="delete_folder" data-applies-to="folder" data-menu="delete"><?php echo lang('albums:delete') ?></li>
                <li data-applies-to="folder file pane" data-menu="details"><?php echo lang('albums:details') ?></li>
                <li data-applies-to="file" data-menu="make_public">Make it Public</li>
                <li data-applies-to="file" data-menu="make_private">Make it Private</li>
                <li data-applies-to="file" data-menu="make_protected">Make it Protected</li>
            </ul>

        </section>

        <section class="side sidebar-right animated" id="file-sidebar">
            <button class="close" alt="close">Close</button>
            <ul id="search-results"></ul>
        </section>

        <div class="hidden">

            <script type="text/javascript">

                /*
                 * Put this somewhere safe
                 * - Like at the end of the document
                 */

                $('body').append('<div class="hidden"><div id="files-uploader"><div id="file-to-replace"><h4><?php echo lang("albums:replace_file") ?>:<span class="name"></span></h4><span class="alert-warning"><?php echo lang("albums:replace_warning") ?></span></div><div class="files-uploader-browser"><form action="<?php echo site_url('admin/files/upload') ; ?>"method="post"accept-charset="utf-8"enctype="multipart/form-data"><label for="file"class="upload"><?php echo lang("albums:uploader") ?></label><input type="file"name="file"value=""multiple="multiple"/><input type="hidden"name="replace-id"value=""/></form><div class="buttons"><a href="#"title=""class="button start-upload"><?php echo lang("albums:upload") ?></a><a href="#"title=""class="button cancel-upload"><?php echo lang("buttons:cancel") ; ?></a></div><ul id="files-uploader-queue"class="ui-corner-all"></ul></div></div></div>');

            </script>

            <div id="item-details">
                <h4><?php echo lang('albums:details') ?></h4>
                <ul>
                    <li><label><?php echo lang('albums:id') ?>:</label> 
                        <span class="id"></span>
                    </li>
                    <li><label><?php echo lang('albums:name') ?>:</label> 
                        <span class="name"></span>
                    </li>

                    <li class="show-data">
                        <button><?php echo lang('albums:toggle_data_display') ?></button>
                    </li>
                </ul>

                <ul class="meta-data">
                    <li><label><?php echo lang('albums:slug') ?>:</label> 
                        <span class="slug"></span>
                    </li>
                    <li><label><?php echo lang('albums:path') ?>:</label> 
                        <input readonly="readonly" type="text" class="path"/>
                    </li>
                    <li><label><?php echo lang('albums:added') ?>:</label> 
                        <span class="added"></span>
                    </li>
                    <li><label><?php echo lang('albums:width') ?>:</label> 
                        <span class="width"></span>
                    </li>
                    <li><label><?php echo lang('albums:height') ?>:</label> 
                        <span class="height"></span>
                    </li>
                    <li><label><?php echo lang('albums:filename') ?>:</label> 
                        <span class="filename"></span>
                    </li>
                    <li><label><?php echo lang('albums:filesize') ?>:</label> 
                        <span class="filesize"></span>
                    </li>
                    <li><label><?php echo lang('albums:download_count') ?>:</label> 
                        <span class="download_count"></span>
                    </li>
                    <li><label><?php echo lang('albums:location') ?>:</label> 
                        <span class="location-static"></span>
                    </li>
                    <li><label><?php echo lang('albums:container') ?>:</label> 
                        <span class="container-static"></span>
                    </li>
                    <li><label><?php echo lang('albums:location') ?>:</label> 
                        <?php echo form_dropdown('location', $locations, '', 'class="location"') ?>
                    </li>
                    <li><label><?php echo lang('albums:bucket') ?>:</label> 
                        <?php echo form_input('bucket', '', 'class="container amazon-s3"') ?>
                        <a class="container-button button"><?php echo lang('albums:check_container') ?></a>
                    </li>
                    <li><label><?php echo lang('albums:container') ?>:</label> 
                        <?php echo form_input('container', '', 'class="container rackspace-cf"') ?>
                        <a class="container-button button"><?php echo lang('albums:check_container') ?></a>
                    </li>
                    <li>
                        <span class="container-info"></span>
                    </li>
                </ul>

                <ul class="item-description">
                    <li><label><?php echo lang('albums:alt_attribute') ?>:</label>
                        <input type="text" class="alt_attribute" />
                    </li>
                    <li><label><?php echo lang('albums:keywords') ?>:</label>
                        <?php echo form_input('keywords', '', 'id="keyword_input"') ?>
                    </li>
                    <li><label><?php echo lang('albums:description') ?>:</label> <br />
                        <textarea class="description"></textarea>
                    </li>
                </ul>
                
               
                <div class="buttons">
                    <?php $this->load->view('admin/partials/buttons', array( 'buttons' => array( 'save', 'cancel' ) )) ?>
                </div>
            </div>
<!--            <div id="create-thumbnail">
               <img src="#" id="cropbox" width="630" height="500"/>
                 This is the form that our event handler fills 
                    <input id="thumb_x" type="hidden" name="thumb_x">
                    <input id="thumb_y" type="hidden" name="thumb_y">
                    <input id="thumb_w" type="hidden" name="thumb_w">
                    <input id="thumb_h" type="hidden" name="thumb_h">
                    <div class="buttons">
                        <?php $this->load->view('admin/partials/buttons', array( 'buttons' => array( 'save', 'cancel' ) )) ?>
                    </div>
            </div>-->
             

            <ul>
                <li class="new-folder" data-id="" data-name=""><span class="name-text"><?php echo lang('albums:new_album_name') ?></span></li>
            </ul>
        </div>

    </section>

    <section class="file-path">
        <h5 id="file-breadcrumbs">
            <span id="crumb-root">
                <a data-id="0" href="#"><?php echo lang('albums:places') ?></a>
            </span>
        </h5>
        <h5 id="activity"></h5>
    </section>
</fieldset>