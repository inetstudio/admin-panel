$(document).on('show.bs.modal', '.modal', function () {
    let zIndex = 2050 + (10 * $('.modal.fade.show').length);
    $(this).css('z-index', zIndex);
    setTimeout(function() {
        $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
    }, 0);
});

$(document).on('focusin', function(e) {
    if ($(e.target).closest(".mce-window").length) {
        e.stopImmediatePropagation();
    }
});

$(document).ready(function () {
    $('.json-data').each(function () {
        let json = JSON.parse($(this).text());
        $(this).text(JSON.stringify(json, null, '\t'));
    });

    if ($('[data-src]:not([class*=placeholder])').length > 0) {
        new window.LazyLoad({
            elements_selector: '[data-src]:not([class*=placeholder])'
        });
    }

    $('.code').each(function () {
        let element = $(this);

        window.CodeMirror.fromTextArea(document.getElementById(element.attr('id')), {
            lineNumbers: true,
            matchBrackets: true,
            styleActiveLine: true,
            mode: 'htmlmixed'
        });

        element.closest('.form-group').find('.collapse-link').click();
    });

    $('.countable').each(function () {
        let countSymbols = function (element) {
            let count = element.val().length,
                inputName = element.attr('name');

            $('.'+jq(inputName + '-counter')).text('символов - '+count);
        };

        countSymbols($(this));

        $(this).on('input', function () {
            countSymbols($(this));
        });
    });

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

    $('.select2-drop').each(function () {
        let $this = $(this);
        if ($this.attr('data-source')) {
            let url = $this.attr('data-source'),
                exclude = (typeof $this.attr('data-exclude') !== 'undefined') ? $this.attr('data-exclude').split('|').map(Number) : [];

            let options = {};

            if ($this.attr('data-create') === '1') {
                options.tags = true;
            }

            if ($this.parents('.modal').length > 0) {
                options.dropdownParent = $this.parents('.modal').first();
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
                minimumInputLength: 1
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
                objectID = $('#object-id').val(),
                url = $this.attr('data-slug-url'),
                target = $this.attr('data-slug-target');

            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    id: objectID,
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
        let $table = $button.closest('.dataTable');


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
                            $table.DataTable().ajax.reload(null, false);
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

/**
 * Вспомогательная функция для использования служебных символов в селекторе
 * @param selector
 */
function jq(selector) {
    return selector.replace(/(:|\.|\[|\]|,|=|@)/g, "\\$1");
}

window.waitForElement = function(selector, callback) {
    if ($(selector).length) {
        callback();
    } else {
        setTimeout(function() {
            window.waitForElement(selector, callback);
        }, 100);
    }
};

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
