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
    import CodeMirror from 'codemirror/lib/codemirror';

    import "codemirror/lib/codemirror.css";
    import "codemirror/addon/scroll/simplescrollbars.css";

    export default {
        name: 'BaseCode',
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

            component.$nextTick(function () {
              let codeMirror = CodeMirror.fromTextArea(component.$refs.editor, {
                lineNumbers: true,
                matchBrackets: true,
                styleActiveLine: true,
                mode: 'htmlmixed'
              });

              codeMirror.on('change', function(cm) {
                component.$emit('update:value', cm.getValue());
              });
            });
        },
        mixins: [
            window.Admin.vue.mixins['errors']
        ]
    }
</script>
