<template>
    <div>
        <div class="form-group row" :class="{'has-error': hasError}">
            <label :for="name" class="col-sm-2 col-form-label font-bold">{{ label }}</label>
            <div class="col-sm-10">
                <input :name="name" type="text" :value="value" :id="name" class="form-control" v-bind="attributes" @input="$emit('update:value', $event.target.value)" ref="autocomplete">

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
        name: 'BaseAutocomplete',
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
        mounted() {
            let component = this;

            component.$nextTick(function () {
                let $field = $(component.$refs.autocomplete);

                let options = {
                    type: 'POST',
                    paramName: 'q',
                    params: {
                        type: 'autocomplete'
                    },
                    minChars: 2,
                    onSelect: function (suggestion) {
                        component.$emit('select', {
                            data: suggestion.data
                        });
                    }
                };

                let url = $field.attr('data-search');
                if (url) {
                    options.serviceUrl = url;
                }

                $field.autocomplete(options);
            });
        },
        mixins: [
            window.Admin.vue.mixins['errors']
        ]
    }
</script>

<style scoped>

</style>
