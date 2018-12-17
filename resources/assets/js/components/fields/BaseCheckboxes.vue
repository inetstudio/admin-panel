<template>
    <div>
        <div class="form-group" v-bind:class="{'has-error': hasError}" ref="group">
            <label v-bind:for="name" class="col-sm-2 control-label">{{ label }}</label>
            <div class="col-sm-10">
                <div class="i-checks"
                     v-for = "(checkbox, index) in checkboxes"
                     :key = index
                >
                    <label>
                        <input
                            type="checkbox"
                            v-bind:value="checkbox.value"
                            v-bind="attributes"
                        /> {{ checkbox.label}}
                    </label>
                </div>

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
        name: 'BaseCheckboxes',
        props: {
            label: {
                type: String,
                default: ''
            },
            name: {
                type: String,
                required: true
            },
            checkboxes: {
                type: Array,
                default() {
                    return [];
                }
            },
            selected: {
                type: Array,
                default() {
                    return [];
                }
            },
            attributes: {
                type: Object,
                default() {
                    return {};
                }
            }
        },
        watch: {
            selected: function (newValues, oldValues) {
                let checkboxes = $(this.$refs.group).find('.i-checks input');

                checkboxes.filter(function () {
                    return ! _.includes(newValues, $(this).val());
                }).iCheck('uncheck');

                checkboxes.filter(function () {
                    return _.includes(newValues, $(this).val());
                }).iCheck('check');
            }
        },
        mounted() {
            let component = this;

            this.$nextTick(function () {
                let checkboxes = $(component.$refs.group).find('.i-checks');

                checkboxes.iCheck({
                    checkboxClass: 'icheckbox_square-green',
                    radioClass: 'iradio_square-green'
                });

                checkboxes.find('input').on('ifToggled', function () {
                    let selected = $(component.$refs.group).find('input:checked').map(function() {
                        return $(this).val();
                    }).get();

                    component.$emit('update:selected', selected)
                });
            });
        },
        mixins: [
            window.Admin.vue.mixins['errors']
        ]
    }
</script>

<style scoped>

</style>
