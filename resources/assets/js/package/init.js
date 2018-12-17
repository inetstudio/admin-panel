window.Admin = window.Admin || {
    stores:[],
    options: {},
    modals: {},
    containers: {
        images: [],
        lists: []
    },
    modules: {},
    vue: {
        stores: [],
        mixins: [],
        modulesComponents: new Vue({
            el: '#modules-components',
            data: {
                modules: {}
            },
        })
    }
};

window.Admin.options.toastr = {
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

window.Admin.options.tinymce = {
    skin: false,
    skin_url: false,
    branding: false,
    height: 500,
    menubar: false,
    automatic_uploads: false,
    drag_drop_upload: false,
    drag_drop: false,
    relative_urls: false,
    remove_script_host: false,
    language: 'ru',
    plugins: [
        'autolink lists links charmap print preview',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime media table contextmenu paste images widgets'
    ],
    toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | outdent indent | add_link | images | code | add_embedded_widget'
};
