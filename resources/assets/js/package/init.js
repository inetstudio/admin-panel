window.Admin = window.Admin || {
    stores:[],
    options: {},
    modals: {},
    containers: {
        images: [],
        lists: []
    },
    modules: {}
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
        'insertdatetime media table contextmenu paste media widgets products_package_products_items'
    ],
    toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | outdent indent | add_link | media | code | add_embedded_widget | add_products_items_list_widget'
};
