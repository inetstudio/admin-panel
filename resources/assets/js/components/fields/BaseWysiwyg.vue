<template>
    <div>
        <div class="form-group row" :class="{'has-error': hasError}">
            <label :for="name" class="col-sm-2 col-form-label font-bold">{{ label }}</label>
            <div class="col-sm-10">
                <textarea :name="name" type="text" :value="value" :id="getId()" class="form-control" v-bind="attributes" ref="editor" />

                <span class="form-text m-b-none"
                      v-for = "(error, index) in fieldErrors"
                      :key = index
                >{{ error}}</span>
            </div>
        </div>
        <div class="hr-line-dashed"></div>
    </div>
</template>

<script>
    export default {
        name: 'BaseWysiwyg',
        props: {
            label: {
                type: String,
                default: ''
            },
            name: {
                type: String,
                required: true
            },
            value: [String, Number],
            simple: {
                type: Boolean,
                default: false
            },
            attributes: {
                type: Object,
                default() {
                    return {};
                }
            }
        },
        methods: {
            getId() {
                return _.get(this.attributes, 'id', this.name);
            }
        },
        mounted() {
            let component = this;

            let options = {
              setup: function(editor) {
                editor.on('change', function (event) {
                  component.$emit('update:value', editor.getContent());
                });
              }
            };

            component.$nextTick(function () {
                if (component.simple) {
                  window.tinymce.init(_.merge({
                    skin: false,
                    skin_url: false,
                    target: component.$refs.editor,
                    height: 300,
                    menubar: false,
                    toolbar: false,
                    statusbar: false
                  }, options));
                } else {
                  options.target = component.$refs.editor;
                  window.tinymce.init(_.merge(window.Admin.options.tinymce, options));
                }
            });
        },
        mixins: [
            window.Admin.vue.mixins['errors']
        ]
    }
</script>
