window.$ = window.jQuery = require('jquery');
global.$ = $;
global.jQuery = jQuery;

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': token.content
        }
    });
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

require('bootstrap');

window.Pace = require('./plugins/pace/pace');
window.Pace.start();

window.Clipboard = require('clipboard');
window.Holder = require('holderjs');
window.LazyLoad = require('vanilla-lazyload');
window.swal = require('sweetalert2/dist/sweetalert2');
window.Sortable = require('sortablejs');
window.toastr = require('toastr');
window.UUID = require('uuidjs');
window.Vue = require('vue');

window.tinymce = require('tinymce');
require('tinymce-i18n/langs/ru');
require('tinymce/themes/modern/theme');
require('tinymce/plugins/autolink');
require('tinymce/plugins/lists');
require('tinymce/plugins/link');
require('tinymce/plugins/charmap');
require('tinymce/plugins/print');
require('tinymce/plugins/preview');
require('tinymce/plugins/searchreplace');
require('tinymce/plugins/visualblocks');
require('tinymce/plugins/code');
require('tinymce/plugins/fullscreen');
require('tinymce/plugins/insertdatetime');
require('tinymce/plugins/media');
require('tinymce/plugins/table');
require('tinymce/plugins/contextmenu');
require('tinymce/plugins/paste');

require( 'datatables.net-bs');

require('metismenu');
require('jquery-slimscroll');
require('jquery-serializejson');

require('icheck');

require('select2/dist/js/select2.full');
require('select2/dist/js/i18n/ru');

require('devbridge-autocomplete');
require('jquery-datetimepicker/build/jquery.datetimepicker.full');

require('@fancyapps/fancybox/dist/jquery.fancybox');

require('./package/init.js');

const requireFromPackages = function(r){
    r.keys().forEach(function (key) {
        if (! key.includes('admin-panel')) {
            r(key);
        }
    });
};

requireFromPackages(require.context('../../../../', true, /\app\.js/));

require('./package/inspinia.js');
require('./package/admin-panel.js');
