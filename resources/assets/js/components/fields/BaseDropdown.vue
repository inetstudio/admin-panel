<template>
    <div>
        <div class="form-group" :class="{'has-error': hasError}">
            <label :for="name" class="col-sm-2 control-label">{{ label }}</label>
            <div class="col-sm-10">
                <select :id="name" :name="name" v-model="selected" class="form-control" v-bind="attributes" ref="select" style="width: 100%">
                    <option value=""></option>
                    <option :value="option.value" v-bind="option.attributes" v-for="option in options">{{ option.text }}</option>
                </select>

                <span class="help-block m-b-none"
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
        name: 'BaseDropdown',
        props: {
            label: {
                type: String,
                default: ''
            },
            name: {
                type: String,
                required: true
            },
            options: {
                type: Array,
                default() {
                    return [];
                }
            },
            selected: [String, Array],
            attributes: {
                type: Object,
                default() {
                    return {};
                }
            }
        },
        mounted() {
            let component = this;

            this.$nextTick(function () {
                let select = $(component.$refs.select);

                let options = {
                    language: "ru"
                };

                if (select.attr('data-source')) {
                    let url = select.attr('data-source'),
                        exclude = (typeof select.attr('data-exclude') !== 'undefined') ? select.attr('data-exclude').split('|').map(Number) : [];

                    if (select.attr('data-create') === '1') {
                        options.tags = true;
                    }

                    options = $.extend({
                        ajax: {
                            url: url,
                            method: 'POST',
                            dataType: 'json',
                            delay: 250,
                            data: function (params) {
                                return {
                                    q: params.term
                                };
                            },
                            processResults: function (data) {
                                return {
                                    results: $.map(data.items, function (item) {
                                        if (exclude.indexOf(item.id) === -1) {
                                            return {
                                                text: item.name,
                                                id: item.id
                                            }
                                        }
                                    })
                                };
                            },
                            cache: true
                        },
                        minimumInputLength: 3
                    }, options);
                }

                select.select2(options).on('change', function () {
                    component.$emit('update:selected', this.value)
                });
            });
        },
        mixins: [
            window.Admin.vue.mixins['errors']
        ]
    }
</script>
