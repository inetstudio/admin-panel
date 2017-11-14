$.lazyLoadXT.autoInit = false;

var Admin = Admin || {
    options: {},
    modals: {},
    containers: {
        images: [],
        lists: []
    }
};

Admin.options.toastr = {
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

Admin.options.tinyMCE = {
    selector: '.tinymce',
    height: 500,
    menubar: false,
    automatic_uploads: false,
    drag_drop_upload: false,
    drag_drop: false,
    relative_urls: false,
    remove_script_host: false,
    language: 'ru',
    plugins: [
        'autolink lists link charmap print preview',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime media table contextmenu paste images'
    ],
    toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | outdent indent | link | images | code'
};

$(document).on('show.bs.modal', '.modal', function () {
    var zIndex = 2040 + (10 * $('.modal.fade.in').length);
    $(this).css('z-index', zIndex);
    setTimeout(function() {
        $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
    }, 0);
});

$(document).ready(function () {

    if ($('.json-data').length > 0) {
        $('.json-data').each(function () {
            var json = JSON.parse($(this).text());
            $(this).text(JSON.stringify(json, null, '\t'));
        });
    }

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

    if ($('.dataTable').length > 0) {
        $('.dataTable').each(function () {
            var table = $(this);
            $('.table-group-buttons a').each(function () {
                var btn = $(this);

                btn.on('click', function () {
                    var data = $('.group-element').serializeJSON();

                    swal({
                        title: "Вы уверены?",
                        type: "warning",
                        showCancelButton: true,
                        cancelButtonText: "Отмена",
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Да",
                        closeOnConfirm: true
                    }, function () {
                        $.ajax({
                            url: btn.attr('data-url'),
                            method: "POST",
                            dataType: "json",
                            data: $.extend(data, {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            }),
                            success: function (data) {
                                if (data.success == true) {
                                    swal({
                                        title: "Записи обновлены",
                                        type: "success"
                                    });
                                    table.DataTable().ajax.reload();
                                } else {
                                    swal({
                                        title: "Ошибка",
                                        text: "При обновлении записей произошла ошибка",
                                        type: "error"
                                    });
                                }
                            }
                        });
                    });
                });
            });

            $(this).on('draw.dt', function () {
                if ($('.i-checks').length > 0) {
                    $('.i-checks').iCheck({
                        checkboxClass: 'icheckbox_square-green',
                        radioClass: 'iradio_square-green'
                    });
                }

                if ($('input.switchery').length > 0) {
                    $('input.switchery').each(function (item) {
                        var switchery = new Switchery($(this).get(0), {
                            size: 'small'
                        });

                        var url = ($(this).attr('data-target'));

                        if (url) {
                            $(this).on('change', function () {
                                $.ajax({
                                    url: url,
                                    method: 'POST',
                                    data: {
                                        _token: $('meta[name="csrf-token"]').attr('content')
                                    },
                                    dataType: 'json',
                                    success: function (data) {
                                        if (data.success == true) {
                                            swal({
                                                title: "Запись изменена",
                                                type: "success"
                                            });
                                        } else {
                                            swal({
                                                title: "Ошибка",
                                                text: "Произошла ошибка",
                                                type: "error"
                                            });
                                        }
                                    }
                                });
                            });
                        }
                    });
                }
            });
        });
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
                                toastr.success('', 'Сортировка сохранена', Admin.options.toastr);
                            } else {
                                toastr.error('', 'При изменении сортировки произошла ошибка', Admin.options.toastr);
                            }
                        },
                        'error': function () {
                            toastr.error('', 'При изменении сортировки произошла ошибка', Admin.options.toastr);
                        }
                    });
                }
            })
        });
    }

    if ($('.editable-list').length > 0) {
        var editItemComponent = new Vue({
            el: '#edit_list_item_modal',
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
                        Admin.containers.lists[this.target].items.push(item);
                    }

                    $('#edit_list_item_modal').modal('hide');
                }
            }
        });

        $('.editable-list').each(function() {
            var name = $(this).attr('id'),
                inputs = JSON.parse($(this).attr('data-properties')),
                items = JSON.parse($(this).attr('data-items'));

            Admin.containers.lists[name] = new Vue({
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

                        $('#edit_list_item_modal').modal();
                    },
                    edit: function (index) {
                        editItemComponent.item = {};

                        editItemComponent.mode = 'edit';
                        editItemComponent.target = this.$el.id;
                        editItemComponent.inputs = this.inputs;
                        editItemComponent.item = this.items[index];

                        $('#edit_list_item_modal').modal();
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
                            toastr.success('', 'Порядок изменен', Admin.options.toastr);
                        } else {
                            toastr.error('', 'При изменении порядка произошла ошибка', Admin.options.toastr);
                        }
                    },
                    'error': function () {
                        toastr.error('', 'При изменении порядка произошла ошибка', Admin.options.toastr);
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
                    'three_state': false
                }
            };

            $(this).jstree(options).on('changed.jstree', function (node, action, selected, event) {

                if (list.attr('data-cascade') == 'up') {
                    $.each(action.node.parents, function (key, val) {
                        if (action.instance.get_checked_descendants(val).length > 0) {
                            action.instance.check_node(val);
                        } else {
                            action.instance.uncheck_node(val);
                        }
                    });
                }

                var ids = action.instance.get_selected().map(function (id) {
                    return id.split('_')[1];
                });
                $('input[name=' + targetField + ']').val(ids);
            });
        });
    }

    if ($('.autocomplete').length > 0) {
        $('.autocomplete').each(function () {
            var field = $(this),
                url = field.attr('data-search');

            var options = {
                serviceUrl: url,
                type: 'POST',
                params: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    type: 'autocomplete'
                },
                minChars: 2,
                onSelect: function (suggestion) {
                    field.parent().find('.choose-data').val(JSON.stringify(suggestion.data));
                }
            };

            field.autocomplete(options);
        });
    }

    if ($('.tinymce').length > 0) {
        tinymce.init(Admin.options.tinyMCE);
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

                var options = {};

                if ($this.attr('data-create') == '1') {
                    options.tags = true;
                }

                $(this).select2($.extend({
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
                }, options));
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
