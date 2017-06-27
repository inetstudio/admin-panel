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
        $('.select2').select2();
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
