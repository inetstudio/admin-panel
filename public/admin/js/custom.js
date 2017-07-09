$(document).ready(function() {

    if ($('[data-src]').length > 0) {
        $(window).lazyLoadXT();
    }

    if ($('.tinymce').length > 0) {
        tinymce.init({
            selector: '.tinymce',
            height: 500,
            menubar: false,
            plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table contextmenu paste code'
            ],
            toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image'
        });
    }

    if ($('.select2').length > 0) {
        $.each($('.select2'), function () {
            var $this = $(this);
            if ($this.attr('data-source')) {
                var url = $this.attr('data-source');

                $('.select2').select2({
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
                $('.select2').select2({
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

    if ($('.datepicker').length > 0) {
        $('.datepicker').datepicker({
            todayBtn: 'linked',
            keyboardNavigation: false,
            forceParse: false,
            autoclose: true,
            language: 'ru',
            format: 'yyyy-mm-dd'
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
                    console.log(data);
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
});
