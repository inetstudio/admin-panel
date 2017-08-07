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

$(document).ready(function() {

    if ($('.start-cropper').length > 0) {
        $('.start-cropper').on('click', function(event) {
            event.preventDefault();

            //$('.img-preview').parent().hide();

            $('#crop_modal .modal-title').text($(this).closest('.form-group').children('label').text());
            $('#crop_modal .save').attr('data-crop-field', $(this).attr('data-crop-field'));
            $('#crop_image').attr('data-ratio', $(this).attr('data-ratio'));

            var $cropField = $('[name='+jq($(this).attr('data-crop-field'))+']');
            $('#crop_image').attr('data-values', $cropField.val());

            if (! $('#crop_image').attr('src')) {
                var imageSrc = $(this).closest('.form-group').find('img').attr('src');
                $('#crop_image').attr('src', imageSrc);
                $('#crop_preview').attr('src', imageSrc);
            }

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

            $image.cropper(cropperOptions);
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
                $field = $('[name='+fieldSelector+']'),
                $link = $('[data-crop-field='+fieldSelector+']');

            $field.val(cropData);

            $link.removeClass('btn-default').addClass('btn-primary');

            $('#crop_modal').modal('hide');
        });
    }

    if ($('.inputImage').length > 0) {
        if (window.FileReader) {
            $('.inputImage').change(function () {
                var fileReader = new FileReader(),
                    $input = $(this),
                    files = this.files,
                    field = $input.attr('data-field'),
                    base64 = $('#'+field+'_base64'),
                    filename = $('#'+field+'_filename'),
                    preview = $('#'+field+'_preview img'),
                    cropButtons = $('#'+field+'_crop_buttons'),
                    additionalFields = $('#'+field+'_additional'),
                    crop = $('#crop_image'),
                    crop_preview = $('#crop_preview'),
                    file;

                if (!files.length) {
                    return;
                }

                file = files[0];

                if (/^image\/\w+$/.test(file.type)) {
                    fileReader.readAsDataURL(file);
                    fileReader.onload = function (e) {
                        preview.attr('src', e.target.result);
                        base64.val(e.target.result);
                        filename.val(file.name);
                        crop.attr('src', e.target.result);
                        crop_preview.attr('src', e.target.result);
                        if (preview.hasClass('placeholder')) {
                            Holder.setResizeUpdate(preview.get(0), false);
                        }

                        $input.closest('.form-group').find('.start-cropper').removeClass('btn-primary').addClass('btn-default');
                        $input.closest('.form-group').find('.crop-data').val('');
                        cropButtons.slideDown();
                        additionalFields.slideDown();

                        $input.val('');
                    };
                } else {
                    swal({
                        title: 'Ошибка',
                        text: 'Выберите изображение',
                        type: 'error'
                    });
                }
            });
        } else {
            $('.inputImage').addClass("hide");
        }
    }

    if ($('[data-src]:not([class*=placeholder])').length > 0) {
        $('[data-src]:not([class*=placeholder])').lazyLoadXT();
    }

    if ($('.order-list').length > 0) {
        $('.order-list').each(function() {
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
                        next: ($itemEl.prev().length > 0) ? $itemEl.prev().attr('data-post-id'): 0
                    };

                    $.ajax({
                        'url': sortURL,
                        'type': 'POST',
                        'data': data,
                        'dataType': 'json',
                        'success': function(data) {
                            if (data.success) {
                                toastr.success('', 'Сортировка сохранена', toastrOptions);
                            } else {
                                toastr.error('', 'При изменении сортировки произошла ошибка', toastrOptions);
                            }
                        },
                        'error': function(){
                            toastr.error('', 'При изменении сортировки произошла ошибка', toastrOptions);
                        }
                    });
                }
            })
        });
    }

    if ($('.nested-list').length > 0) {
        $('.nested-list').each(function() {
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
                    'success': function(data) {
                        if (data.success) {
                            toastr.success('', 'Порядок изменен', toastrOptions);
                        } else {
                            toastr.error('', 'При изменении порядка произошла ошибка', toastrOptions);
                        }
                    },
                    'error': function(){
                        toastr.error('', 'При изменении порядка произошла ошибка', toastrOptions);
                    }
                });
            });
        });
    }

    if ($('.jstree-list').length > 0) {
        $('.jstree-list').each(function() {
            var list = $(this),
                targetField = list.attr('data-target');

            var options = {
                'core': {
                    'check_callback': true,
                    'multiple': (list.attr('data-multiple') == 'true')
                },
                'plugins': ['types', 'checkbox'],
                'types': {
                    'default' : {
                        'icon' : 'fa fa-folder'
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
                $('input[name='+targetField+']').val(ids);
            });
        });
    }

    if ($('.tinymce').length > 0) {
        tinymce.init({
            selector: '.tinymce',
            height: 500,
            menubar: false,
            plugins: [
                'advlist autolink lists link charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table contextmenu paste code'
            ],
            toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link'
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
            format:'d.m.Y H:i'
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
                    $('input[name="'+target+'"]').val(data);
                }
            });

        });
    }

    if ($('.fancybox-video-link').length > 0) {
        $('.fancybox-video-link').fancybox({
            onComplete: function() {
                this.$content.find('video').trigger('play');
                this.$content.find('video').on('ended', function() {
                    $.fancybox.close();
                });
            }
        });
    }

    if ($('.clipboard').length > 0) {
        new Clipboard('.clipboard');
    }

    $('.table').on('click', '.delete', function (event) {
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
                        $button.closest('tr').remove();
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
        return selector.replace( /(:|\.|\[|\]|,|=|@)/g, "\\$1" );
    }
});
