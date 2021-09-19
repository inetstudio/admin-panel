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

import Pace from 'pace-js'

window.Pace = Pace;
window.Pace.start();

window.CodeMirror = require('codemirror/lib/codemirror');
window.LazyLoad = require('vanilla-lazyload');

/* VUE ===================================================================== */
import Vue from 'vue';
import Vuex from 'vuex';
import * as VueJSModal from 'vue-js-modal';

window.Vue = Vue;
window.Vuex = Vuex;
window.Vue.use(window.Vuex);
window.Vue.use(VueJSModal);

window.Admin.vue = {
    stores: [],
    mixins: [],
    modulesComponents: document.getElementById('modules-components')
        ? new window.Vue({
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

window.Vue.component('vue-block-buttons', () => import('./components/blocks/ButtonsComponent.vue'));
window.Vue.component('vue-block-info', () => import('./components/blocks/InfoComponent.vue'));
window.Vue.component('BaseInputHidden', () => import('./components/fields/BaseInputHidden.vue'));
window.Vue.component('BaseInputText', () => import('./components/fields/BaseInputText.vue'));
window.Vue.component('BaseCheckboxes', () => import('./components/fields/BaseCheckboxes.vue'));
window.Vue.component('BaseAutocomplete', () => import('./components/fields/BaseAutocomplete.vue'));
window.Vue.component('BaseDropdown', () => import('./components/fields/BaseDropdown.vue'));
window.Vue.component('BaseDate', () => import('./components/fields/BaseDate.vue'));
window.Vue.component('BaseWysiwyg', () => import('./components/fields/BaseWysiwyg.vue'));
window.Vue.component('BaseCode', () => import('./components/fields/BaseCode.vue'));

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
