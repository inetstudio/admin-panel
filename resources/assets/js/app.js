const requireFromPackages = function(r){
    r.keys().forEach(function (key) {
        if (! key.includes('admin-panel')) {
            r(key);
        }
    });
};

require('./package/init.js');

window.$ = window.jQuery = require('jquery');
global.$ = $;
global.jQuery = jQuery;
window._ = require('lodash');
window.Popper = require('popper.js').default;

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': token.content
        }
    });
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

require('bootstrap');

window.Pace = require('./plugins/pace/pace');
window.Pace.start();

window.Clipboard = require('clipboard');
window.CodeMirror = require('codemirror/lib/codemirror');
window.hash = require('object-hash');
window.Holder = require('holderjs');
window.LazyLoad = require('vanilla-lazyload');
window.swal = require('sweetalert2/dist/sweetalert2');
window.Sortable = require('sortablejs');
window.toastr = require('toastr');
window.UUID = require('uuidjs');


/* VUE ===================================================================== */
import Vue from 'vue';
import Vuex from 'vuex';
import * as VueJSModal from 'vue-js-modal';

Vue.use(Vuex);
Vue.use(VueJSModal)

window.Admin.vue = {
    stores: [],
    mixins: [],
    modulesComponents: document.getElementById('modules-components')
        ? new Vue({
            el: '#modules-components',
            data: {
                modules: {}
            },
        })
        : null,
    helpers: {
        initVueModule: function (moduleName) {
            if (window.Admin.vue.modulesComponents && ! window.Admin.vue.modulesComponents.modules.hasOwnProperty(moduleName)) {
                let moduleComponents = {};
                moduleComponents[moduleName] = {
                    components: []
                };

                window.Admin.vue.modulesComponents.modules = Object.assign(
                    {},
                    window.Admin.vue.modulesComponents.modules,
                    moduleComponents
                );
            }
        },
        initComponent: function (moduleName, componentName, data) {
            this.initVueModule(moduleName);

            if (window.Admin.vue.modulesComponents && typeof window.Admin.vue.modulesComponents.$refs[moduleName + '_' + componentName] == 'undefined') {
                window.Admin.vue.modulesComponents.modules[moduleName].components = _.union(
                    window.Admin.vue.modulesComponents.modules[moduleName].components,
                    [
                        {
                            name: componentName,
                            data: data,
                        }
                    ]
                );
            }
        },
        getVueComponent: function (moduleName, componentName) {
            if (typeof window.Admin.vue.modulesComponents.$refs[moduleName + '_' + componentName] == 'undefined') {
                return null;
            }

            return window.Admin.vue.modulesComponents.$refs[moduleName + '_' + componentName][0];
        }
    }
};

require('./mixins/errors');

Vue.component('vue-block-buttons', require('./components/blocks/ButtonsComponent.vue').default);
Vue.component('vue-block-info', require('./components/blocks/InfoComponent.vue').default);
Vue.component('BaseInputHidden', require('./components/fields/BaseInputHidden.vue').default);
Vue.component('BaseInputText', require('./components/fields/BaseInputText.vue').default);
Vue.component('BaseCheckboxes', require('./components/fields/BaseCheckboxes.vue').default);
Vue.component('BaseAutocomplete', require('./components/fields/BaseAutocomplete.vue').default);
Vue.component('BaseDropdown', require('./components/fields/BaseDropdown.vue').default);
Vue.component('BaseDate', require('./components/fields/BaseDate.vue').default);
Vue.component('BaseWysiwyg', require('./components/fields/BaseWysiwyg.vue').default);
Vue.component('BaseCode', require('./components/fields/BaseCode.vue').default);

/* VUE ===================================================================== */
window.Admin.helpers = {
    documentReady: function(callback) {
        if (document.readyState !== "loading") {
            callback();
        } else {
            document.addEventListener("DOMContentLoaded", callback);
        }
    }
};



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

require( 'datatables.net-bs4');

require('metismenu');
require('jquery-slimscroll');
require('jquery-serializejson');

require('icheck');

require('select2/dist/js/select2.full');
require('select2/dist/js/i18n/ru');

require('devbridge-autocomplete');
require('codemirror/mode/htmlmixed/htmlmixed');
require('codemirror/addon/scroll/simplescrollbars');
require('jquery-datetimepicker/build/jquery.datetimepicker.full');

require('@fancyapps/fancybox/dist/jquery.fancybox');

requireFromPackages(require.context('../../../../', true, /\app\.js/));

require('./package/inspinia.js');
require('./package/admin-panel.js');
