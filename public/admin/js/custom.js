$.lazyLoadXT.autoInit = false;

var toastrOptions = {
    "closeButton": true,
    "debug": false,
    "progressBar": true,
    "preventDuplicates": false,
    "positionClass": "toast-top-center",
    "onclick": null,
    "showDuration": "1000",
    "hideDuration": "5000",
    "timeOut": "2000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};

$(document).ready(function () {

    if ($('.upload-btn').length > 0) {
        $('.upload-btn').each(function () {
            var $input = $(this),
                url = $input.attr('data-target'),
                field = $input.attr('data-field'),
                name = field + '[file]',
                progressbar = $('#' + field + '_progress').children(),
                filename = $('#' + field + '_filename'),
                tempname = $('#' + field + '_tempname'),
                temppath = $('#' + field + '_temppath'),
                preview = $('#' + field + '_preview img'),
                cropButtons = $('#' + field + '_crop_buttons'),
                additionalFields = $('#' + field + '_additional'),
                crop = $('#crop_image'),
                crop_preview = $('#crop_preview');

            var uploader = new plupload.Uploader({
                browse_button: this,
                url: url,
                filters: {
                    mime_types: "image/*"
                },
                chunk_size: '500kb',
                multi_selection: false,
                file_data_name: name,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                multipart_params: {
                    fieldName: name
                }
            });

            uploader.init();

            uploader.bind('FilesAdded', function (up, files) {
                $('#' + field + '_preview').closest('.ibox-content').toggleClass('sk-loading');
                progressbar.parent().slideDown();
                up.start();
            });

            uploader.bind('UploadProgress', function (up, file) {
                progressbar.width(file.percent + '%');
                progressbar.attr('aria-valuenow', file.percent);
            });

            uploader.bind('FileUploaded', function (up, file, response) {
                $('#' + field + '_preview').closest('.ibox-content').toggleClass('sk-loading');
                progressbar.parent().slideUp();
                progressbar.width('0%');
                progressbar.attr('aria-valuenow', 0);

                response = JSON.parse(response.response);

                preview.attr('src', response.result.tempPath);
                filename.val(file.name);
                tempname.val(response.result.tempName);
                temppath.val(response.result.tempPath);
                crop.attr('src', response.result.tempPath);
                crop_preview.attr('src', response.result.tempPath);
                if (preview.hasClass('placeholder')) {
                    Holder.setResizeUpdate(preview.get(0), false);
                }

                $input.closest('.form-group').find('.start-cropper').removeClass('btn-primary').addClass('btn-default');
                $input.closest('.form-group').find('.crop-data').val('');
                cropButtons.slideDown();
                additionalFields.slideDown();
            });
        });
    }

    if ($('.start-cropper').length > 0) {
        $('.start-cropper').on('click', function (event) {
            event.preventDefault();

            var cropSettings = JSON.parse($(this).attr('data-crop-settings'));

            $('#crop_modal .modal-title').text($(this).closest('.form-group').children('label').text());
            $('#crop_modal .description').text(cropSettings.description);
            $('#crop_modal .save').attr('data-crop-field', $(this).attr('data-crop-field'));
            $('#crop_image').attr('data-ratio', $(this).attr('data-ratio'));

            $('#crop_modal .crop-size').attr('data-width', cropSettings.width);
            $('#crop_modal .crop-size').attr('data-height', cropSettings.height);
            $('#crop_modal .crop-size').attr('data-type', cropSettings.type);

            var $cropField = $('[name=' + jq($(this).attr('data-crop-field')) + ']');
            $('#crop_image').attr('data-values', $cropField.val());

            var imageSrc = $(this).closest('.form-group').find('img').attr('src');
            $('#crop_image').attr('src', imageSrc);
            $('#crop_preview').attr('src', imageSrc);

            $('#crop_modal').modal();
        });

        $('#crop_modal').on('hidden.bs.modal', function () {
            var $image = $('#crop_image');

            $image.cropper('destroy');
            $('#crop_modal .modal-title').text('');
            $('#crop_modal .save').removeAttr('data-crop-field');
            $image.removeAttr('data-ratio');
            $image.removeAttr('data-values');
        });

        $('#crop_modal').on('shown.bs.modal', function () {
            var $image = $('#crop_image');

            var cropperOptions = {
                viewMode: 3,
                preview: "#crop_modal .img-preview",
                ready: function () {
                    //$('.img-preview').parent().show();
                }
            };

            if ($image.attr('data-ratio')) {
                var size = $image.attr('data-ratio').split('/');
                cropperOptions.aspectRatio = parseInt(size[0]) / parseInt(size[1]);
            } else {
                return;
            }

            if ($image.attr('data-values')) {
                cropperOptions.data = JSON.parse($image.attr('data-values'));
            }

            $image.on({
                crop: function (e) {
                    var infoContainer = $('#crop_modal .crop-size'),
                        requiredWidth = infoContainer.attr('data-width'),
                        requiredHeight = infoContainer.attr('data-height'),
                        requiredType = infoContainer.attr('data-type'),
                        width = Math.round(e.width),
                        height = Math.round(e.height);

                    infoContainer.removeClass('label-primary').removeClass('label-danger');

                    switch (requiredType) {
                        case 'min':
                            if (width < requiredWidth || height < requiredHeight) {
                                infoContainer.addClass('label-danger');
                            } else {
                                infoContainer.addClass('label-primary');
                            }
                            break;
                        case 'fixed':
                            if (width != requiredWidth && height != requiredHeight) {
                                infoContainer.addClass('label-danger');
                            } else {
                                infoContainer.addClass('label-primary');
                            }
                            break;
                    }

                    infoContainer.text(width + 'x' + height);
                }
            }).cropper(cropperOptions);
        });

        $('#crop_modal').on('click', '[data-method]', function () {
            var $this = $(this),
                $image = $('#crop_image'),
                data = $this.data(),
                $target,
                result;

            if ($image.data('cropper') && data.method) {
                data = $.extend({}, data); // Clone a new one

                if (typeof data.target !== 'undefined') {
                    $target = $(data.target);

                    if (typeof data.option === 'undefined') {
                        try {
                            data.option = JSON.parse($target.val());
                        } catch (e) {
                            console.log(e.message);
                        }
                    }
                }

                switch (data.method) {
                    case 'rotate':
                        $image.cropper('clear');
                        break;
                }

                result = $image.cropper(data.method, data.option, data.secondOption);

                switch (data.method) {
                    case 'rotate':
                        $image.cropper('crop');
                        break;

                    case 'scaleX':
                    case 'scaleY':
                        $(this).data('option', -data.option);
                        break;
                }

                if ($.isPlainObject(result) && $target) {
                    try {
                        $target.val(JSON.stringify(result));
                    } catch (e) {
                        console.log(e.message);
                    }
                }

            }
        });

        $('#crop_modal').on('click', '.save', function (event) {
            event.preventDefault();

            var $image = $('#crop_image'),
                cropData = JSON.stringify($image.cropper('getData')),
                fieldSelector = jq($(this).attr('data-crop-field')),
                $field = $('[name=' + fieldSelector + ']'),
                $link = $('[data-crop-field=' + fieldSelector + ']');

            $field.val(cropData);

            $link.removeClass('btn-default').addClass('btn-primary');

            $('#crop_modal').modal('hide');
        });
    }

    if ($('[data-src]:not([class*=placeholder])').length > 0) {
        $('[data-src]:not([class*=placeholder])').lazyLoadXT();
    }

    if ($('.order-list').length > 0) {
        $('.order-list').each(function () {
            var sortURL = $(this).attr('data-sort-url');
            Sortable.create(this, {
                dataIdAttr: 'data-post-id',
                handle: '.post-drag',
                onUpdate: function (evt) {
                    var $itemEl = $(evt.item);

                    var data = {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        currentId: $itemEl.attr('data-post-id'),
                        prev: ($itemEl.next().length > 0) ? $itemEl.next().attr('data-post-id') : 0,
                        next: ($itemEl.prev().length > 0) ? $itemEl.prev().attr('data-post-id') : 0
                    };

                    $.ajax({
                        'url': sortURL,
                        'type': 'POST',
                        'data': data,
                        'dataType': 'json',
                        'success': function (data) {
                            if (data.success) {
                                toastr.success('', 'Сортировка сохранена', toastrOptions);
                            } else {
                                toastr.error('', 'При изменении сортировки произошла ошибка', toastrOptions);
                            }
                        },
                        'error': function () {
                            toastr.error('', 'При изменении сортировки произошла ошибка', toastrOptions);
                        }
                    });
                }
            })
        });
    }

    if ($('.editable-list').length > 0) {
        var listComponents = [];

        var editItemComponent = new Vue({
            el: '#modal_edit_item',
            data: {
                mode: '',
                target: '',
                item: {},
                inputs: []
            },
            methods: {
                save: function () {
                    var item = this.item;

                    $(this.$el).find('input').each(function () {
                        item.properties[$(this).attr('name')] = $(this).val();
                    });

                    if (this.mode == 'add') {
                        listComponents[this.target].items.push(item);
                    }

                    $('#modal_edit_item').modal('hide');
                }
            }
        });

        $('.editable-list').each(function() {
            var name = $(this).attr('id'),
                inputs = JSON.parse($(this).attr('data-properties')),
                items = JSON.parse($(this).attr('data-items'));

            listComponents[name] = new Vue({
                el: '#'+name,
                data: {
                    items: items,
                    inputs: inputs
                },
                methods: {
                    add: function (index) {
                        editItemComponent.mode = 'add';
                        editItemComponent.target = this.$el.id;
                        editItemComponent.inputs = this.inputs;

                        var properties = {};
                        $.each(this.inputs, function (key, value) {
                            properties[value.name] = "";
                        });

                        editItemComponent.item = {
                            properties: properties
                        };

                        $('#modal_edit_item').modal();
                    },
                    edit: function (index) {
                        editItemComponent.item = {};

                        editItemComponent.mode = 'edit';
                        editItemComponent.target = this.$el.id;
                        editItemComponent.inputs = this.inputs;
                        editItemComponent.item = this.items[index];

                        $('#modal_edit_item').modal();
                    },
                    remove: function (index) {
                        this.$delete(this.items, index);
                    }
                },
                computed: {
                    itemTitles: function() {
                        return this.items.map(function(item) {
                            return item.properties[Object.keys(item.properties)[0]];
                        });
                    }
                }
            });
        });
    }

    if ($('.nested-list').length > 0) {
        $('.nested-list').each(function () {
            var orderURL = $(this).attr('data-order-url');

            $(this).nestable({
                group: 1
            }).on('change', function (e) {
                var list = e.length ? e : $(e.target);

                var data = {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    data: window.JSON.stringify(list.nestable('serialize'))
                };

                $.ajax({
                    'url': orderURL,
                    'type': 'POST',
                    'data': data,
                    'dataType': 'json',
                    'success': function (data) {
                        if (data.success) {
                            toastr.success('', 'Порядок изменен', toastrOptions);
                        } else {
                            toastr.error('', 'При изменении порядка произошла ошибка', toastrOptions);
                        }
                    },
                    'error': function () {
                        toastr.error('', 'При изменении порядка произошла ошибка', toastrOptions);
                    }
                });
            });
        });
    }

    if ($('.jstree-list').length > 0) {
        $('.jstree-list').each(function () {
            var list = $(this),
                targetField = list.attr('data-target');

            var options = {
                'core': {
                    'check_callback': true,
                    'multiple': (list.attr('data-multiple') == 'true')
                },
                'plugins': ['types', 'checkbox'],
                'types': {
                    'default': {
                        'icon': 'fa fa-folder'
                    }
                },
                'checkbox': {
                    'three_state': false,
                    'cascade': list.attr('data-cascade')
                }
            }

            $(this).jstree(options).on('changed.jstree', function (e, data) {
                var ids = list.jstree('get_selected').map(function (id) {
                    return id.split('_')[1];
                });
                $('input[name=' + targetField + ']').val(ids);
            });
        });
    }

    if ($('.tinymce').length > 0) {
        var imagesContainer = [];

        var uploaderModal = new Vue({
            el: '#uploader_modal',
            data: {
                target: '',
                upload: true,
                progress: {
                    state: false,
                    percents: 0,
                    text: '',
                    style: {
                        width: '0%'
                    }
                },
                images: [],
                inputs: []
            },
            methods: {
                save: function (event) {
                    var target = this.target;

                    $.each(this.images, function (key, image) {
                        imagesContainer[target].images.push(image);
                    });

                    $('#uploader_modal').modal('hide');

                    this.images.splice(0);
                    this.upload = true;
                }
            }
        });

        var imageModal = new Vue({
            el: '#edit_image_modal',
            data: {
                target: '',
                image: {},
                inputs: []
            },
            methods: {
                save: function () {
                    $('#edit_image_modal').modal('hide');
                }
            }
        });


        tinymce.init({
            selector: '.tinymce',
            height: 500,
            menubar: false,
            automatic_uploads: false,
            drag_drop_upload: false,
            drag_drop: false,
            relative_urls: false,
            remove_script_host: false,
            content_css: '/assets/css/article-page.min.css',
            body_class: 'article-content',
            visual_table_class: 'table-compare',
            plugins: [
                'autolink lists link charmap print preview',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table contextmenu paste'
            ],
            toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | skin_bullist skin_bullist-h skin_numlist skin_numlist-h | outdent indent | link | longread_link longread longread_clear | images | skin_blockquote | code | bg_pink bg_green bg_beige | important | block_pointer block_pointer-preview block_pointer-ingr block_pointer-expert | add_table_styles add_drop',

            setup: function (editor) {
                if ($(editor.getElement()).get(0).hasAttribute('hasImages')) {

                    var $input = $('#uploader-area'),
                        url = $input.attr('data-target'),
                        name = editor.id,
                        images = JSON.parse($('#' + name + '_images').attr('data-media'));

                    imagesContainer[name] = new Vue({
                        el: '#' + name + '_images',
                        data: {
                            images: images,
                            inputs: JSON.parse($(editor.getElement()).attr('properties'))
                        },
                        methods: {
                            add: function (index) {
                                tinymce.get(name).editorManager.execCommand('mceInsertContent', false, '<div class="article-block_img"><img src="' + this.images[index].src + '" alt="' + this.images[index].properties.alt + '"><p class="fix">' + this.images[index].properties.description + '</p></div>');
                            },
                            edit: function (index) {
                                var modalWindow = $('#edit_image_modal');

                                imageModal.target = name;
                                imageModal.image = this.images[index];
                                imageModal.inputs = this.inputs;

                                modalWindow.modal();
                            },
                            remove: function (index) {
                                this.$delete(this.images, index);
                            }
                        }
                    });

                    editor.addButton('images', {
                        title: 'Загрузить изображения',
                        icon: 'image',
                        onclick: function () {
                            uploaderModal.images.splice(0);
                            uploaderModal.upload = true;
                            uploaderModal.target = editor.id;
                            uploaderModal.inputs = JSON.parse($(editor.getElement()).attr('properties'));

                            var uploader = new plupload.Uploader({
                                browse_button: 'uploader-area',
                                drop_element: 'uploader-area',
                                url: url,
                                filters: {
                                    mime_types: "image/*"
                                },
                                chunk_size: '500kb',
                                multi_selection: true,
                                file_data_name: name,
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                multipart_params: {
                                    fieldName: name
                                }
                            });

                            uploader.init();

                            uploader.bind('FilesAdded', function (up) {
                                uploaderModal.progress.state = true;
                                uploaderModal.upload = false;
                                up.start();
                            });

                            uploader.bind('UploadProgress', function (up) {
                                uploaderModal.progress.percents = up.total.percent;
                                uploaderModal.progress.text = up.total.percent + '% (' + (up.total.uploaded + 1) + ' из ' + up.files.length + ')';
                                uploaderModal.progress.style.width = up.total.percent + '%';
                            });

                            uploader.bind('FileUploaded', function (up, file, response) {
                                response = JSON.parse(response.response);

                                var properties = {};
                                $.each(uploaderModal.inputs, function (key, value) {
                                    properties[value.name] = "";
                                });

                                uploaderModal.images.push({
                                    src: response.result.tempPath,
                                    tempname: response.result.tempName,
                                    filename: file.name,
                                    properties: properties
                                });
                            });

                            uploader.bind('UploadComplete', function (up) {
                                uploaderModal.progress.state = false;
                                uploaderModal.progress.percents = 0;
                                uploaderModal.progress.text = '';
                                uploaderModal.progress.style.width = '0%';
                                up.destroy()
                            });

                            uploaderModal.upload = true;
                            $('#uploader_modal').modal();
                        }
                    });
                }

                editor.addButton('longread_link', {
                    title: 'Содержание лонгрида',
                    icon: 'anchor',
                    onclick: function () {

                        editor.focus();

                        var num = 1;

                        editor.execCommand('mceInsertContent', false, '<div class="article-block_anchors"><p class="article-block_anchors-h">Содержание</p><ul class="article-block_anchors-list"><li data-target="' + num + '">' + editor.selection.getContent() + '</li></ul></div>');

                        editor.on('keyup', function (e) {


                            var anchBlock = $(editor.selection.getNode()).closest('.article-block_anchors');

                            // console.log(anchBlock);

                            if (e.keyCode == 13 && anchBlock.length > 0) {

                                num++;

                                anchBlock.find('li:last').attr('data-target', num);

                            }
                        });

                    }
                });

                var longreadCounter = 1;
                editor.addButton('longread', {
                    title: 'Лонгрид',
                    image: '/admin/img/admin-13.png',

                    onclick: function () {
                        editor.focus();

                        console.log(editor.selection.getNode());

                        editor.execCommand('mceInsertContent', false, '<div class="article-block" data-anchor="' + longreadCounter + '">' + editor.selection.getContent() + '<div class="article-backtop"><a href="">Вернуться к оглавлению</a></div></div>');

                        longreadCounter++;
                    }
                });

                editor.addButton('longread_clear', {
                    title: 'Сброс связей лонгрида',
                    image: '/admin/img/admin-14.png',

                    onclick: function () {

                        if (longreadCounter === 1) return;
                        longreadCounter--;
                    }
                });


                // block with pointer to left column

                editor.addButton('block_pointer', {
                    title: 'Блок с указателем',
                    image: '/admin/img/admin-3.png',
                    onclick: function () {
                        editor.focus();
                        editor.execCommand('mceReplaceContent', false, '<div class="article-block_pointer"><div class="article-block_text">' + editor.selection.getContent() + '</div><div style="background:grey" class="article-aside_left"></div></div>');
                    }
                });

                editor.addButton('block_pointer-preview', {
                    title: 'Превью статьи для блока с указателем',
                    image: '/admin/img/admin-10.png',
                    onclick: function () {
                        editor.focus();
                        editor.execCommand('mceReplaceContent', false, '<div class="article-block_pointer"><div class="article-block_text">' + editor.selection.getContent() + '</div><div style="background:grey" class="article-aside_left"></div></div>');
                    }
                });

                editor.addButton('block_pointer-ingr', {
                    title: 'Ингредиент для блока с указателем',
                    image: '/admin/img/admin-11.png',
                    onclick: function () {
                        editor.focus();
                        editor.execCommand('mceReplaceContent', false, '<div class="article-block_pointer"><div class="article-block_text">' + editor.selection.getContent() + '</div><div style="background:grey" class="article-aside_left"></div></div>');
                    }
                });

                editor.addButton('block_pointer-expert', {
                    title: 'Мнение эксперта для блока с указателем',
                    image: '/admin/img/admin-12.png',
                    onclick: function () {
                        editor.focus();
                        editor.execCommand('mceReplaceContent', false, '<div class="article-block_pointer"><div class="article-block_text">' + editor.selection.getContent() + '</div><div style="background:grey" class="article-aside_left"></div></div>');
                    }
                });


                // add drop to table

                editor.addButton('add_drop', {
                    title: 'Добавить каплю в таблицу',
                    image: '/admin/img/admin-4.png',
                    onclick: function () {
                        editor.focus();
                        editor.execCommand('mceInsertContent', false, '<i class="icon-drop-glare">&nbsp;</i>');
                    }
                });


                // add table styles

                editor.addButton('add_table_styles', {
                    title: 'Сформировать таблицу',
                    image: '/admin/img/admin-5.png',
                    onclick: function () {
                        editor.focus();
                        editor.execCommand('mceReplaceContent', false, '<div class="article-block_compare"><div class="article-block_compare-h"><p>Введите название таблицы</p></div><div class="article-block_compare-table">' + editor.selection.getContent() + '</div></div>');
                    }
                });

                //blockqoute
                editor.addButton('skin_blockquote', {
                    title: 'Цитата',
                    icon: 'blockquote',
                    onclick: function () {
                        editor.focus();
                        editor.execCommand('mceReplaceContent', false, '<blockquote class="article-block_blockquote">' + editor.selection.getContent() + '</blockquote>');
                    }
                });

                // pink background
                editor.addButton('bg_pink', {
                    title: 'Фон - розовый',
                    image: '/admin/img/admin-8.png',

                    onclick: function () {
                        editor.focus();


                        console.log(editor.selection.getNode());

                        var content = $(editor.selection.getNode());

                        if (content.is('body')) {
                            content = editor.selection.getContent();

                            editor.execCommand('mceInsertContent', false, '<div class="article-block_text"><div class="pink-bg">' + content + '</div></div>');

                        } else if (content.is('.simple')) {

                            content.addClass('pink-bg');

                        } else if (content.closest('.article-block_text-important')) {

                            content.closest('.article-block_text-important').addClass('pink-bg')

                        } else {

                            content.wrap('<div class="article-block_text"><div class="pink-bg"></div></div>');

                        }

                    }
                });

                // green background
                editor.addButton('bg_green', {
                    title: 'Фон - зеленый',
                    image: '/admin/img/admin-7.png',
                    onclick: function () {
                        editor.focus();


                        // console.log(editor.selection.getNode());

                        var content = $(editor.selection.getNode());

                        if (content.is('body')) {
                            content = editor.selection.getContent();

                            editor.execCommand('mceInsertContent', false, '<div class="article-block_text"><div class="green-light-bg">' + content + '</div></div>');

                        } else if (content.is('.simple')) {

                            content.addClass('green-light-bg');

                        } else if (content.closest('.article-block_text-important')) {

                            content.closest('.article-block_text-important').addClass('green-light-bg')

                        } else {

                            content.wrap('<div class="article-block_text"><div class="green-light-bg"></div></div>');

                        }

                    }
                });

                // beige background
                editor.addButton('bg_beige', {
                    title: 'Фон - бежевый',
                    image: '/admin/img/admin-9.png',

                    onclick: function () {
                        editor.focus();


                        // console.log(editor.selection.getNode());

                        var content = $(editor.selection.getNode());

                        if (content.is('body')) {
                            content = editor.selection.getContent();

                            editor.execCommand('mceInsertContent', false, '<div class="article-block_text"><div class="beige-bg">' + content + '</div></div>');

                        } else if (content.is('.simple')) {

                            content.addClass('beige-bg');

                        } else if (content.closest('.article-block_text-important')) {

                            content.closest('.article-block_text-important').addClass('beige-bg')

                        } else {

                            content.wrap('<div class="article-block_text"><div class="beige-bg"></div></div>');

                        }

                    }
                });

                var endId = tinymce.DOM.uniqueId();

                // console.log(endId);

                // help event CTRL + N - exit from block to the end of the content
                editor.on('keydown', function (e) {

                    if ((e.metaKey || e.ctrlKey) && ( String.fromCharCode(e.which).toLowerCase() === 'n')) {

                        var newNode = editor.dom.select('span#' + endId);

                        console.log(newNode);

                        if (newNode.length > 0) {
                            editor.selection.select(newNode[0]);
                        } else {
                            editor.dom.add(editor.getBody(), 'span', {'id': endId}, 'Конец документа');

                            var newNode = editor.dom.select('span#' + endId);
                            editor.selection.select(newNode[0]);
                        }

                    }
                });


                // block with important text
                editor.addButton('important', {
                    title: 'Важно',
                    image: '/admin/img/admin-6.png',
                    onclick: function () {
                        editor.focus();

                        var content = $(editor.selection.getNode());
                        // console.log(content);

                        if (content.closest('.article-block_text').length > 0) {

                            content.closest('.article-block_text').addClass('article-block_text-important');

                        } else {

                            content.wrap('<div class="article-block_text article-block_text-important"></div>');

                        }

                    }
                });

                // simple unordered list
                editor.addButton('skin_bullist', {
                    title: 'Простой маркированный список',
                    icon: 'bullist',
                    onclick: function () {

                        var content;

                        editor.focus();
                        if (editor.selection.getContent()) {
                            content = editor.selection.getContent();
                        } else {
                            content = '&nbsp;';
                        }


                        var text = editor.selection.getContent({'format': 'html'});

                        if (text && text.length > 0) {
                            editor.execCommand('mceInsertContent', false, '<div class="article-block_text"><ul class="simple"><li>' + text + '</li></ul></div>');
                        }

                    }
                });


                // unordered list-chapter with or without heading
                editor.addButton('skin_bullist-h', {
                    title: 'Список-глава (с заголовком и без)',
                    image: '/admin/img/admin-1.png',
                    onclick: function () {

                        var content;

                        editor.focus();
                        if (editor.selection.getContent()) {
                            content = editor.selection.getContent();
                        } else {
                            content = '&nbsp;';
                        }

                        editor.execCommand('mceInsertContent', false, '<div class="article-block_text"><ul class="chapter"><li><p class="fix">' + content + '</p></li></ul></div>');


                        editor.on('keyup', function (e) {


                            var ul = $(editor.selection.getNode()).closest('ul.chapter');

                            if (!(e.metaKey || e.ctrlKey) && (e.keyCode == 13) && ul.length > 0) {

                                ul.find('li:last').append('<p class="fix">Текст пункта</p>');

                            }
                        });


                    }
                });

                // simple ordered list
                editor.addButton('skin_numlist', {
                    title: 'Простой нумерованный список',
                    icon: 'numlist',
                    onclick: function () {
                        editor.focus();

                        var num = 1;

                        editor.execCommand('mceInsertContent', false, '<div class="article-block_text"><ol class="simple"><li><span class="num">' + num + '</span>' + editor.selection.getContent() + '</li></ol></div>');


                        editor.on('keyup', function (e) {

                            // console.log(num);

                            var ol = $(editor.selection.getNode()).closest('ol.simple');

                            if ((e.keyCode == 13) && ol.length > 0) {

                                num++;

                                ol.find('li:last').prepend('<span class="num">' + num + '</span>');
                                ol.find('li:last').append('Текст пункта');


                            }
                        });


                    }
                });

                // ordered list-chapter with or without heading
                editor.addButton('skin_numlist-h', {
                    title: 'Нумерованный список-глава (с заголовком и без)',
                    image: '/admin/img/admin-2.png',
                    onclick: function () {

                        var num = 1;


                        editor.execCommand('mceInsertContent', false, '<div class="article-block_text"><ol class="chapter"><li><span class="chapter-i">' + num + '</span><p class="fix">' + editor.selection.getContent() + '</p></li></ol></div>');

                        editor.on('keyup', function (e) {

                            // console.log(num);

                            var ol = $(editor.selection.getNode()).closest('ol.chapter');

                            if (!(e.metaKey || e.ctrlKey) && (e.keyCode == 13) && ol.length > 0) {

                                num++;

                                ol.find('li:last').prepend('<span class="chapter-i">' + num + '</span>');
                                ol.find('li:last').append('<p class="fix">Текст пункта</p>');


                            }
                        });

                    }
                });
            }
        });
    }

    if ($('.tinymce-simple').length > 0) {
        tinymce.init({
            selector: '.tinymce-simple',
            height: 300,
            menubar: false,
            toolbar: false,
            statusbar: false
        });
    }

    if ($('.select2').length > 0) {
        $.each($('.select2'), function () {
            var $this = $(this);
            if ($this.attr('data-source')) {
                var url = $this.attr('data-source');

                $(this).select2({
                    language: "ru",
                    ajax: {
                        url: url,
                        method: 'POST',
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                q: params.term,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            };
                        },
                        processResults: function (data) {
                            return {
                                results: $.map(data.items, function (item) {
                                    return {
                                        text: item.name,
                                        id: item.id
                                    }
                                })
                            };
                        },
                        cache: true
                    },
                    minimumInputLength: 3
                });
            } else {
                $(this).select2({
                    language: "ru"
                });
            }
        });
    }

    if ($('.i-checks').length > 0) {
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green'
        });
    }

    if ($('.datetimepicker').length > 0) {
        $.datetimepicker.setLocale('ru');

        $('.datetimepicker').datetimepicker({
            format: 'd.m.Y H:i'
        });
    }

    if ($('.slugify').length > 0) {
        $('.slugify').on('change', function () {
            var $this = $(this);
            var val = $this.val(),
                url = $this.attr('data-slug-url'),
                target = $this.attr('data-slug-target');

            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    name: val,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function (data) {
                    $('input[name="' + target + '"]').val(data);
                }
            });

        });
    }

    if ($('.fancybox-video-link').length > 0) {
        $('.fancybox-video-link').fancybox({
            onComplete: function () {
                this.$content.find('video').trigger('play');
                this.$content.find('video').on('ended', function () {
                    $.fancybox.close();
                });
            }
        });
    }

    if ($('.clipboard').length > 0) {
        new Clipboard('.clipboard');
    }

    $('.table, .dd-list').on('click', '.delete', function (event) {
        event.preventDefault();

        var $button = $(this);

        swal({
            title: "Вы уверены?",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: "Отмена",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Да, удалить",
            closeOnConfirm: true
        }, function () {
            $.ajax({
                url: $button.attr('data-url'),
                method: "POST",
                dataType: "json",
                data: {
                    _method: "DELETE",
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data.success == true) {
                        $button.closest('tr, .dd3-item').remove();
                        swal({
                            title: "Запись удалена",
                            type: "success"
                        });
                    } else {
                        swal({
                            title: "Ошибка",
                            text: "При удалении произошла ошибка",
                            type: "error"
                        });
                    }
                }
            });
        });
    });

    /**
     * Вспомогательная функция для использования служебных символов в селекторе
     * @param selector
     */
    function jq(selector) {
        return selector.replace(/(:|\.|\[|\]|,|=|@)/g, "\\$1");
    }
});
