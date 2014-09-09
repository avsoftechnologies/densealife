// Functions declarations for easy global access
// Image cropping
var ias, updateScaleRatios, previewThumb, updateCoords;
// Image selector
var updateSelected, enableThumbSelection, updateFolderContent;
// Map
var checkMode, centerMap, geocodePlace, refreshMap;
// Helper
var refreshSrc, inputSync;
jQuery(function($) {
    if ($('input[name="comment_permission"]:checked').val() == 'FOLLOWER') {
        $('#tr-ca').show();
    } else {
        $('#tr-ca').hide();
        $('input[name="comment_approval"]').removeAttr('checked');
    }
    $('input[name="comment_permission"]').change(function() {
        if ($(this).val() == 'FOLLOWER') {
            $('#tr-ca').show();
        } else {
            $('input[name="comment_approval"]').removeAttr('checked');
            $('#tr-ca').hide();
        }
    });
    // Generate a slug when the user types a title in
    $('input[name="title"]').keyup(function() {
        slug = $('span#slug');
        slug_field = $('input[name="slug"]');
        $.post(SITE_URL + "ajax/url_title", {
            title: $(this).val()
        }, function(new_slug) {
            if (new_slug === "past")
                $(slug).css('color', 'red');
            else
                $(slug).css('color', '');
            slug.text(new_slug);
            $('#event-url').attr('href', '/eventsmanager/' + new_slug);
            slug_field.val(new_slug);
        });
    });

    // Modify the URL hash to enable refreshing the page but stay on the current tab
    $('a.tab').click(function() {
        window.location.hash = $(this).attr('href');
    });


    /**************************************
     * Datepicker manager
     *************************************/

    // Localization disabled because it causes problems with date output
   // $.datepicker.setDefaults($.datepicker.regional[CURRENT_LANGUAGE]);
    $("input.datepicker").datepicker({
        dateFormat: JS_DATE_FORMAT
    });

    // Permet d'ajouter un champ "date de fin"
    $("#add_end_date").click(function() {
        $('#end_date_form').fadeIn();
        $("#add_end_date").css('display', 'none');
        $('input[name=end_date]').attr('value', $('input[name=start_date]').val());
        var start_hour = $('select[name=start_time_hour] option:selected').val();
        var start_minute = $('select[name=start_time_minute] option:selected').val();
        $('select[name="end_time_hour"]').val(parseInt(start_hour) + DATE_INTERVAL);
        $('select[name="end_time_hour"]').trigger("liszt:updated");
        $('select[name="end_time_minute"]').val(parseInt(start_minute));
        $('select[name="end_time_minute"]').trigger("liszt:updated");
        $('input#end_date_defined').val(1);
    });

    // Permet de supprimer le champ "date de fin"
    $("#remove_end_date").click(function() {
        $('#end_date_form').fadeOut();
        $("#add_end_date").css('display', 'inline');
        // On ne définit plus la date de fin
        $('input#end_date_defined').val(0);
    });


    $('textarea#about').ckeditor({
        toolbar: [
            ['Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink']
        ],
        height: 100,
        dialog_backgroundCoverColor: '#000',
        language: CURRENT_LANGUAGE
    });

    $('textarea#description').ckeditor({
        toolbar: [
            ['Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink']
        ],
        height: 200,
        dialog_backgroundCoverColor: '#000',
        language: CURRENT_LANGUAGE
    });

    /**************************************
     * Thumbnail crop manager
     *************************************/

    /* Functions for the image cropper */
    var ratio_w = 1;
    var ratio_h = 1;

    updateScaleRatios = function() {
        img = document.getElementById('selected-picture');
        ratio_w = $("#selected-picture").attr('data-width') / img.clientWidth;
        ratio_h = $("#selected-picture").attr('data-height') / img.clientHeight;
    }

    /**
     * Change dynamically an image source
     */
    refreshSrc = function(img, src) {
        $(img).attr('src', 'uploads/default/files/' + src + "?" + Math.floor(Math.random() * 10000)); // Refresh the source
    }

    // Preview the cropped thumbnail
    previewThumb = function(img, selection) {
        var scaleX = 100 / (selection.width || 1);
        var scaleY = 100 / (selection.height || 1);
        $('#cropped-picture-preview').css({
            width: Math.round(scaleX * img.width) + 'px',
            height: Math.round(scaleY * img.height) + 'px',
            marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px',
            marginTop: '-' + Math.round(scaleY * selection.y1) + 'px'
        });
        $('input#prev-coord-x1').val(Math.round(ratio_w * selection.x1));
        $('input#prev-coord-y1').val(Math.round(ratio_h * selection.y1));
        $('input#prev-coord-x2').val(Math.round(ratio_w * selection.x2));
        $('input#prev-coord-y2').val(Math.round(ratio_h * selection.y2));
    }

    // Put the selection coordinates into hidden inputs
    updateCoords = function() {
        var selection = ias.getSelection();
        var x1 = selection.x1;
        var y1 = selection.y1;
        var x2 = selection.x2;
        var y2 = selection.y2;
        $('input[name=thumbnail_x1]').val(x1);
        $('input[name=thumbnail_y1]').val(y1);
        $('input[name=thumbnail_x2]').val(x2);
        $('input[name=thumbnail_y2]').val(y2);
    }

    // Enable cropping tool : ImgAreaSelect
    ias = $('img#selected-picture').imgAreaSelect({
        instance: true,
        handles: true,
        aspectRatio: "1:1",
        keys: true,
        fadeSpeed: 300,
        onSelectStart: updateScaleRatios,
        onSelectChange: previewThumb,
        onSelectEnd: updateCoords
    });

    // Old Ajax mode, no more used
    /*$('#thumb-refresh-btn').click(function() {
      $('#picture-thumbnail').attr('src',BASE_URI+"addons/default/modules/eventsmanager/img/loading.gif");
      var selection = ias.getSelection();
      var src = $('img#selected-picture').attr('src');
      var disp_w = $('img#selected-picture').css('width');
      var disp_h = $('img#selected-picture').css('height');
      var event_id = $('input#event-id').val();

      $.post(SITE_URL + 'admin/eventsmanager/ajax_img_crop',
        { // POST variables
          x1: selection.x1,
          x2: selection.x2,
          y1: selection.y1,
          y2: selection.y2,
          img_src: src,
          disp_w: disp_w,
          disp_h: disp_h,
          event_id: event_id
        },
        function(data) { // What to do with the data
          refreshSrc('#picture-thumbnail', data);
          $('input[name=thumbnail]').attr('value', data);
        });
    });*/

    // Permet d'éviter d'avoir une sélection qui reste au premier plan sur les autres onglets du formulaire
    //  $('ul.tab-menu li a[href!=#event-picture]').click(function()
    //  {
    //    ias.cancelSelection();
    //  });

    // Get the display width & height of the raw image before submit (important for cropping)
    $('button[type=submit]').click(function() {
        var disp_w = $('img#selected-picture').css('width').replace('px', '');
        var disp_h = $('img#selected-picture').css('height').replace('px', '');
        $('input[name=thumbnail_disp_w]').val(disp_w);
        $('input[name=thumbnail_disp_h]').val(disp_h);
    });

    /**************************************
     * Thumbnail selector
     *************************************/

    updateSelected = function(radio) {
        true_width = $(radio).attr('data-width');
        true_height = $(radio).attr('data-height');
        $("#selected-picture").attr('data-width', true_width);
        $("#selected-picture").attr('data-height', true_height);
        $("#selected-picture").one('load', updateScaleRatios); // Very important ! Enable the load event actually when the image is completely loaded
        refreshSrc("#selected-picture", $(radio).attr('filename'));
        refreshSrc("#cropped-picture-preview", $(radio).attr('filename'));
    }

    updateSelectedCoverPhoto = function(radio) {
        true_width = $(radio).attr('data-width');
        true_height = $(radio).attr('data-height');
        $("#cp-selected-picture").attr('data-width', true_width);
        $("#cp-selected-picture").attr('data-height', true_height);
        $("#cp-selected-picture").one('load', updateScaleRatios); // Very important ! Enable the load event actually when the image is completely loaded
        refreshSrc("#cp-selected-picture", $(radio).attr('filename'));
        refreshSrc("#cp-cropped-picture-preview", $(radio).attr('filename'));
    }

    /* I may use 'live' jquery event instead ! */
    enableThumbSelection = function() {
        $('div#choosing > ul#folder-content > li > input[type=radio]').click(function() {
            updateSelected(this);
        });
        $('div#choosing > ul#folder-content > li > img').click(function() {
            var radio = $(this).siblings('input[type=radio]');
            $(radio).attr('checked', 'checked');
            updateSelected(radio);
        });
    }

    enableCoverPhotoSelection = function() {
        $('div#choosing > ul#cp-folder-content > li > input[type=radio]').click(function() {
            updateSelectedCoverPhoto(this);
        });
        $('div#choosing > ul#cp-folder-content > li > img').click(function() {
            var radio = $(this).siblings('input[type=radio]');
            $(radio).attr('checked', 'checked');
            updateSelectedCoverPhoto(radio);
        });
    }

    enableThumbSelection();
    enableCoverPhotoSelection();

    // update the folder images preview when folder selection changes
    $('select[name=folder_select]').change(function() {

        $.get(SITE_URL + 'admin/eventsmanager/ajax_select_folder/' + $(this).val(), function(data) {

            if (data) {
                var selected_pic_id = $('input#picture_id').val();

                /* ! CALLBACK FUNCTION ! */
                updateFolderContent = function() {
                    $('ul#folder-content').empty();
                    $('div#message').empty();

                    if (data.images && data.images.length > 0) {
                        $.each(data.images, function(i, image) {
                            if (image.id == selected_pic_id)
                                var checked = 'checked = "checked"';
                            else
                                var checked = '';
                            $('ul#folder-content').append(
                                '<li>' +
                                '<img src="' + SITE_URL + 'files/thumb/' + image.id + '" alt="' + image.name + '" title="Title: ' + image.name + ' -- Caption: ' + image.description + '" filename="' + image.filename + '" />' + '<input type="radio" class="picture_id" name="picture_id" value="' + image.id + '" ' + checked + 'filename="' + image.filename + '" data-width="' + image.width + '" data-height="' + image.height + '" >' +
                                '</li>'
                            );
                        });
                        enableThumbSelection();
                        $('ul#folder-content').fadeIn(300);
                    } else {
                        $('div#choosing > div#message').append('<p>' + LANG_NO_IMAGE + '</p>');
                    }
                };

                // Remove all images of the lastest folder
                // Then, in callback, update the list with the new content
                $('ul#folder-content').fadeOut(300, updateFolderContent);

            } else {
                $('div#choosing > div#message').append('<p>' + LANG_AJAX_ERROR + '</p>');
            }

        }, 'json');
    });

    /**************************************
     * Map manager
     *************************************/

    checkMode = function() {
        if ($('input[name=pos_method]:checked').val() == 1)
            $('li#map_latlng_inputs').css('display', '');
        else
            $('li#map_latlng_inputs').css('display', 'none');
    }
    var MAP_INIT = false;
    $('a[href="#map-tab"]').click(function() {
        if (!MAP_INIT) {
            refreshMap();
            MAP_INIT = true;
        }
    });
    $('a#map_options').click(function() {
        $('a[href="#map-tab"]').click();
    });
    $('input[name=pos_method]').change(function() {
        checkMode();
    });

    inputSync = function(field1_str, field2_str) {
        var field1 = $(field1_str);
        var field2 = $(field2_str);
        alert(field1.attr('type'));
        alert(field2.attr('type'));
        if (field1.attr('type') === field2.attr('type')) {
            type = field1.attr('type');
            switch (type) {
                case "text":
                    $(field1).change(function() {
                        content = field1.val();
                        field2.val(content);
                    });
                    $(field2).change(function() {
                        content = field2.val();
                        field1.val(content);
                    });
                    break;

                case "checkbox":
                    $(field1).change(function() {
                        if (this.checked) field2.attr('checked', '');
                        else field2.removeAttr('checked');
                    });
                    $(field2).change(function() {
                        if (this.checked) field1.attr('checked', '');
                        else field1.removeAttr('checked');
                    });
                    break;
                default:
                    break;
            }

        } else {
            console.error("You can't synchronize 2 inputs of different types !");
        }
    };

   // inputSync('input[name=place]', 'input[name=place_clone]');
    //inputSync('input[name=show_map]', 'input[name=show_map_clone]');

    checkMode();
});