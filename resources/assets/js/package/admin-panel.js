$(document).on('show.bs.modal', '.modal', function () {
    let zIndex = 2040 + (10 * $('.modal.fade.in').length);
    $(this).css('z-index', zIndex);
    setTimeout(function() {
        $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
    }, 0);
});

$(document).ready(function () {
    $('.json-data').each(function () {
        let json = JSON.parse($(this).text());
        $(this).text(JSON.stringify(json, null, '\t'));
    });

    if ($('[data-src]:not([class*=placeholder])').length > 0) {
        new LazyLoad({
            elements_selector: '[data-src]:not([class*=placeholder])'
        });
    }

    $('.autocomplete').each(function () {
        let field = $(this),
            url = field.attr('data-search'),
            target = field.attr('data-target');

        let options = {
            serviceUrl: url,
            type: 'POST',
            paramName: 'q',
            params: {
                type: 'autocomplete'
            },
            minChars: 2,
            onSelect: function (suggestion) {
                if (typeof target !== 'undefined') {
                    $(target).val(JSON.stringify(suggestion.data)).trigger('change');
                }
            }
        };

        field.autocomplete(options);
    });

    initTinyMCE('form');

    $('.select2').each(function () {
        let $this = $(this);
        if ($this.attr('data-source')) {
            let url = $this.attr('data-source'),
                exclude = (typeof $this.attr('data-exclude') !== 'undefined') ? $this.attr('data-exclude').split('|').map(Number) : [];

            let options = {};

            if ($this.attr('data-create') === '1') {
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
                            q: params.term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data.items, function (item) {
                                if (exclude.indexOf(item.id) === -1) {
                                    return {
                                        text: item.name,
                                        id: item.id
                                    }
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

    if ($('.i-checks').length > 0) {
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green'
        });
    }

    if ($('.datetimepicker').length > 0) {
        $.datetimepicker.setLocale('ru');

        $('.datetimepicker').each(function () {
            let fieldOptions = $(this).attr('data-options');
            let extOptions = (typeof fieldOptions === 'undefined') ? {} : JSON.parse(fieldOptions);

            let options = $.extend({format: 'd.m.Y H:i'}, extOptions);

            $(this).datetimepicker(options);
        });
    }

    if ($('.slugify').length > 0) {
        $('.slugify').on('change', function () {
            let $this = $(this);
            let val = $this.val(),
                url = $this.attr('data-slug-url'),
                target = $this.attr('data-slug-target');

            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    name: val
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

        let $button = $(this);

        swal({
            title: "Вы уверены?",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: "Отмена",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Да, удалить",
            closeOnConfirm: true
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: $button.attr('data-url'),
                    method: "POST",
                    dataType: "json",
                    data: {
                        _method: "DELETE"
                    },
                    success: function (data) {
                        if (data.success === true) {
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
            }
        });
    });
});

window.initTinyMCE = function (container) {
    $(container).find('.tinymce').each(function () {
        window.Admin.options.tinymce.target = $(this).get(0);
        window.tinymce.init(window.Admin.options.tinymce);
    });

    $(container).find('.tinymce-simple').each(function () {
        window.tinymce.init({
            skin: false,
            skin_url: false,
            target: $(this).get(0),
            height: 300,
            menubar: false,
            toolbar: false,
            statusbar: false
        });
    });
};
