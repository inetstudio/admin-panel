$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

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

    $('.json-data').each(function () {
        var json = JSON.parse($(this).text());
        $(this).text(JSON.stringify(json, null, '\t'));
    });

    if ($('[data-src]:not([class*=placeholder])').length > 0) {
        $('[data-src]:not([class*=placeholder])').lazyLoadXT();
    }

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
                        data: data,
                        success: function (data) {
                            if (data.success === true) {
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


            $('input.switchery').each(function () {
                new Switchery($(this).get(0), {
                    size: 'small'
                });

                var url = ($(this).attr('data-target'));

                if (url) {
                    $(this).on('change', function () {
                        $.ajax({
                            url: url,
                            method: 'POST',
                            dataType: 'json',
                            success: function (data) {
                                if (data.success === true) {
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
        });
    });

    $('.order-list').each(function () {
        var sortURL = $(this).attr('data-sort-url');
        Sortable.create(this, {
            dataIdAttr: 'data-post-id',
            handle: '.post-drag',
            onUpdate: function (evt) {
                var $itemEl = $(evt.item);

                var data = {
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

                    if (this.mode === 'add') {
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

    $('.nested-list').each(function () {
        var orderURL = $(this).attr('data-order-url');

        $(this).nestable({
            group: 1
        }).on('change', function (e) {
            var list = e.length ? e : $(e.target);

            var data = {
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

        $(this).nestable('collapseAll');
    });

    $('.jstree-list').each(function () {
        var list = $(this),
            targetField = list.attr('data-target');

        var options = {
            'core': {
                'check_callback': true,
                'multiple': (list.attr('data-multiple') === 'true')
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

        $(this).jstree(options).on('changed.jstree', function (node, action) {

            if (list.attr('data-cascade') === 'up') {
                if (typeof action.node !== 'undefined') {
                    $.each(action.node.parents, function (key, val) {
                        if (action.instance.get_checked_descendants(val).length > 0) {
                            action.instance.check_node(val);
                        } else {
                            action.instance.uncheck_node(val);
                        }
                    });
                }
            }

            var ids = action.instance.get_selected().map(function (id) {
                return id.split('_')[1];
            });
            $('input[name=' + targetField + ']').val(ids);
        });
    });

    $('.autocomplete').each(function () {
        var field = $(this),
            url = field.attr('data-search'),
            target = field.attr('data-target');

        var options = {
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
        var $this = $(this);
        if ($this.attr('data-source')) {
            var url = $this.attr('data-source'),
                exclude = (typeof $this.attr('data-exclude') !== 'undefined') ? $this.attr('data-exclude').split('|').map(Number) : [];

            var options = {};

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
            var fieldOptions = $(this).attr('data-options');
            var extOptions = (typeof fieldOptions === 'undefined') ? {} : JSON.parse(fieldOptions);

            var options = $.extend({format: 'd.m.Y H:i'}, extOptions);

            $(this).datetimepicker(options);
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

    $('.fullcalendar').each(function () {
        var eventsURL = $(this).attr('data-events'),
            changeURL = $(this).attr('data-change');

        $(this).fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            navLinks: true,
            weekNumbers: true,
            weekNumbersWithinDays: true,
            eventLimit: true,
            editable: true,
            eventDrop: function(event, delta, revertFunc) {
                swal({
                    title: "Вы уверены?",
                    type: "info",
                    showCancelButton: true,
                    cancelButtonText: "Отмена",
                    confirmButtonColor: "#1ab394",
                    confirmButtonText: "Да",
                    closeOnConfirm: true
                }, function(isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url: changeURL,
                            method: "POST",
                            dataType: "json",
                            data: {
                                id: event.id,
                                type: event.type,
                                time: event.start.format()
                            },
                            success: function (data) {
                                if (data.success === true) {
                                    swal({
                                        title: data.message,
                                        type: "success"
                                    });
                                } else {
                                    swal({
                                        title: "Ошибка",
                                        text: data.message,
                                        type: "error"
                                    });
                                }
                            }
                        });
                    } else {
                        revertFunc();
                    }
                });
            },
            timeFormat: 'H:mm',
            events: {
                url: eventsURL
            },
            eventRender: function(event, element) {
                tippy(element.get(), {
                    onShow: function () {
                        const content = this.querySelector('.tippy-content');

                        content.innerHTML = event.tooltip;
                    },
                    html: '#eventTooltip',
                    trigger: 'click',
                    interactive: true,
                    arrow: true,
                    theme: 'light'
                });
            }
        });
    });

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
        });
    });
});

function initTinyMCE(container) {
    $(container).find('.tinymce').each(function () {
        Admin.options.tinyMCE.target = $(this).get(0);
        tinymce.init(Admin.options.tinyMCE);
    });

    $(container).find('.tinymce-simple').each(function () {
        tinymce.init({
            target: $(this).get(0),
            height: 300,
            menubar: false,
            toolbar: false,
            statusbar: false
        });
    });
}

/**
 * Вспомогательная функция для использования служебных символов в селекторе
 * @param selector
 */
function jq(selector) {
    return selector.replace(/(:|\.|\[|\]|,|=|@)/g, "\\$1");
}

function getTimestamp() {
    return new Date().getTime();
}

