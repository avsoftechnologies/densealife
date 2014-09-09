this["CoverPhotoTemplates"] = this["CoverPhotoTemplates"] || {};

this["CoverPhotoTemplates"]["src/templates/actions.jst"] = function(obj) {
    var __p = '';
    var print = function() {
        __p += Array.prototype.join.call(arguments, '')
    };
    with (obj || {}) {
        __p += '<div class="actions">\n  <ul class="chooser">\n    <li class="open-menu item"><a href="#change_cover_photo">Change cover photo</a></li>\n    <ul class="sub-menu">\n      <li class="upload item"><a href="#upload_cover_photo">Upload new photo</a></li>\n    </ul>\n  </ul>\n  <ul class="edit">\n    <li class="cancel item"><a href="#cancel">Cancel</a></li>\n    <li class="save item"><a href="#save">Save</a></li>\n  </ul>\n</div>';
    }
    return __p;
};

this["CoverPhotoTemplates"]["src/templates/container.jst"] = function(obj) {
    var __p = '';
    var print = function() {
        __p += Array.prototype.join.call(arguments, '')
    };
    with (obj || {}) {
        __p += '<div class="coverphoto-container">\n  <canvas class=\'output\'>\n</div>';
    }
    return __p;
};

this["CoverPhotoTemplates"]["src/templates/form.jst"] = function(obj) {
    var __p = '';
    var print = function() {
        __p += Array.prototype.join.call(arguments, '')
    };
    with (obj || {}) {
        __p += '<form action="' +
                (post.url) +
                '" class="coverphoto-form" method="post" enctype="multipart/form-data">\n  <input type="file" name="coverphoto[original]" accept="image/*">\n  <input type="hidden" name="' +
                (post.field) +
                '">\n</form>';
    }
    return __p;
};

this["CoverPhotoTemplates"]["src/templates/image.jst"] = function(obj) {
    var __p = '';
    var print = function() {
        __p += Array.prototype.join.call(arguments, '')
    };
    with (obj || {}) {
        __p += '<div class="coverphoto-photo-container">\n  <img src="' +
                (imageData) +
                '" width="' +
                (imageWidth) +
                '">\n</div>';
    }
    return __p;
};
(function() {
    var __bind = function(fn, me) {
        return function() {
            return fn.apply(me, arguments);
        };
    },
            __slice = [].slice;

    (function($) {
        var CoverPhoto;
        CoverPhoto = (function() {

            CoverPhoto.defaults = {
                editable: false,
                post: {
                    url: null,
                    field: 'coverphoto[cropped]'
                }
            };

            function CoverPhoto(_arg) {
                this.el = _arg.el, this.options = _arg.options;
                this.handleFileSelected = __bind(this.handleFileSelected, this);

                this.endReposition = __bind(this.endReposition, this);

                this.startReposition = __bind(this.startReposition, this);

                this.cancelEdit = __bind(this.cancelEdit, this);

                this.saveEdit = __bind(this.saveEdit, this);

                this.handleCoverPhotoUpdated = __bind(this.handleCoverPhotoUpdated, this);

                this.startUpload = __bind(this.startUpload, this);

                this.hideEditMenu = __bind(this.hideEditMenu, this);

                this.showEditMenu = __bind(this.showEditMenu, this);

                this.hideActionsMenu = __bind(this.hideActionsMenu, this);

                this.showActionsMenu = __bind(this.showActionsMenu, this);

                this.showActions = __bind(this.showActions, this);

                this.hideActions = __bind(this.hideActions, this);

                this.options = $.extend(true, CoverPhoto.defaults, this.options);
                this.templates = CoverPhotoTemplates;
                this.setEl();
                this.render();
                this.elements();
                this.bindEvents();
            }

            CoverPhoto.prototype.render = function() {
                this.addForm();
                if (this.options.editable) {
                    this.addActions();
                }
                if (this.options.currentImage) {
                    this.addImage(this.options.currentImage);
                }
                //$(".actions", this.$el).css("top", this.$el.height() - 35);
                $(".actions", this.$el).css("top", "156px");
                $("canvas", this.$el).attr("width", this.$el.width());
                return $("canvas", this.$el).attr("height", this.$el.height());
            };

            CoverPhoto.prototype.on = function() {
                var args, evt, handler, selector;
                args = 1 <= arguments.length ? __slice.call(arguments, 0) : [];
                if (args.length === 3) {
                    evt = args[0], selector = args[1], handler = args[2];
                    return $(this.$el).delegate(selector, evt, handler);
                } else if (args.length === 2) {
                    evt = args[0], handler = args[1];
                    return $(this.$el).bind(evt, handler);
                }
            };

            CoverPhoto.prototype.bindEvents = function() {
                this.on("coverPhotoUpdated", this.handleCoverPhotoUpdated);
                this.on("mouseleave", this.hideActions);
                this.on("mouseenter", this.showActions);
                this.on("mouseleave", this.actionsContainer.selector, this.hideActionsMenu);
                this.on("change", this.fileInput.selector, this.handleFileSelected);
                this.on("click", this.openMenuButton.selector, this.showActionsMenu);
                this.on("click", this.uploadButton.selector, this.startUpload);
                this.on("click", this.repositionButton.selector, this.startReposition);
                this.on("click", this.saveEditButton.selector, this.saveEdit);
                return this.on("click", this.cancelEditButton.selector, this.cancelEdit);
            };

            CoverPhoto.prototype.setEl = function() {
                var html;
                html = this.templates["src/templates/container.jst"]();
                return this.$el = $(html).appendTo($(this.el));
            };

            CoverPhoto.prototype.elements = function() {
                this.actionsContainer = $(".actions", this.$el);
                this.actions = $(".chooser", this.$el);
                this.actionsMenu = $(".chooser .sub-menu", this.$el);
                this.editMenu = $(".edit", this.$el);
                this.cancelEditButton = $(".edit .cancel a", this.$el);
                this.saveEditButton = $(".edit .save a", this.$el);
                this.openMenuButton = $(".chooser .open-menu a", this.$el);
                this.uploadButton = $(".chooser .upload a", this.$el);
                this.repositionButton = $(".chooser .reposition a", this.$el);
                this.form = $("form", this.$el);
                this.fileInput = $("input[name='coverphoto[original]']", this.$el);
                this.hiddenImageInput = $("input[name='coverphoto[cropped]']", this.$el);
                return this.canvas = $("canvas", this.$el);
            };

            CoverPhoto.prototype.addForm = function() {
                return this.$el.append(this.templates["src/templates/form.jst"](this.options));
            };

            CoverPhoto.prototype.addActions = function() {
                return this.$el.append(this.templates["src/templates/actions.jst"](this.options));
            };

            CoverPhoto.prototype.addImage = function(imageData) {
                var imageWidth;
                imageWidth = this.$el.width();
                this.originalImage = $(".coverphoto-photo-container img", this.$el).attr("src");
                $(".coverphoto-photo-container", this.$el).remove();
                return this.$el.append(this.templates["src/templates/image.jst"]({
                    imageData: imageData,
                    imageWidth: imageWidth
                }));
            };

            CoverPhoto.prototype.hideActions = function() {
                return this.actions.fadeOut();
            };

            CoverPhoto.prototype.showActions = function() {
                if (!this.editMenu.is(":visible")) {
                    return this.actions.fadeIn();
                }
            };

            CoverPhoto.prototype.showActionsMenu = function() {
                this.actionsMenu.show();
                return false;
            };

            CoverPhoto.prototype.hideActionsMenu = function(evt) {
                return this.actionsMenu.hide();
            };

            CoverPhoto.prototype.showEditMenu = function() {
                this.editMenu.show();
                return this.actions.hide();
            };

            CoverPhoto.prototype.hideEditMenu = function() {
                this.editMenu.hide();
                return this.actions.show();
            };

            CoverPhoto.prototype.startUpload = function() {
                this.fileInput.click();
                return false;
            };

            CoverPhoto.prototype.resetFileInputField = function() {
                var form;
                form = this.fileInput.parent();
                this.fileInput.remove();
                $('<input type="file" name="coverphoto[original]" accept="image/*">').appendTo(form);
                return this.fileInput = $("input[name='coverphoto[original]']", this.$el);
            };

            CoverPhoto.prototype.handleCoverPhotoUpdated = function(evt, dataUrl) {
                if (this.options.post.url != null) {
                    return this.form.submit();
                }
            };

            CoverPhoto.prototype.saveEdit = function() {
                var dataUrl;
                dataUrl = this.gatherImageData();
                this.resetFileInputField();
                this.hideEditMenu();
                this.endReposition();
                console.log(dataUrl);
                this.$el.trigger("coverPhotoUpdated", dataUrl);
                return false;
            };

            CoverPhoto.prototype.cancelEdit = function() {
                if (this.originalImage) {
                    this.addImage(this.originalImage);
                }
                this.hideEditMenu();
                this.endReposition();
                return false;
            };

            CoverPhoto.prototype.startReposition = function(evt) {
                var image, makeImageDraggable,
                        _this = this;
                if (evt == null) {
                    evt = null;
                }
                image = $(".coverphoto-photo-container img", this.$el);
                makeImageDraggable = function() {
                    var pPos, yMax;
                    pPos = image.parents(".coverphoto-container").offset();
                    yMax = -(image.height() - image.parent().height() - pPos.top);
                    return image.draggable({
                        axis: "y",
                        containment: [0, yMax, 0, pPos.top]
                    });
                };
                if (image.height() > 0) {
                    makeImageDraggable();
                } else {
                    image.load(makeImageDraggable);
                }
                this.showEditMenu();
                if (evt != null) {
                    this.hideActionsMenu();
                }
                return false;
            };

            CoverPhoto.prototype.endReposition = function() {
                var image;
                image = $(".coverphoto-photo-container img", this.$el);
                return image.draggable("destroy");
            };

            CoverPhoto.prototype.handleFileSelected = function(evt) {
                var file, reader,
                        _this = this;
                file = evt.target.files[0];
                reader = new FileReader();
                reader.onload = function(evt) {
                    _this.addImage(evt.target.result);
                    return _this.startReposition();
                };
                return reader.readAsDataURL(file);
            };

            CoverPhoto.prototype.gatherImageData = function() {
                var context, dataUrl, height, image, width;
                image = $(".coverphoto-photo-container img", this.$el);
                context = this.canvas[0].getContext("2d");
                width = image.width();
                height = image.height();
                context.drawImage(image[0], 0, image.position().top, width, height);
                dataUrl = this.canvas[0].toDataURL("image/png");
                this.hiddenImageInput.val(dataUrl);
                return dataUrl;
            };

            return CoverPhoto;

        })();
        return $.fn.CoverPhoto = function(data) {
            return this.each(function() {
                return new CoverPhoto({
                    el: this,
                    options: data
                });
            });
        };
    })($);

}).call(this);
