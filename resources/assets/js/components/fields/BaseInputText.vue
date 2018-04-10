<template>
    <div>
        <div class="form-group" :class="{'has-error': hasError}">
            <label :for="name" class="col-sm-2 control-label">{{ label }}</label>
            <div class="col-sm-10">
                <input :name="name" type="text" :value="value" :id="name" class="form-control" v-bind="attributes" @input="$emit('update:value', $event.target.value)">

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
        name: 'input-text',
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
            },
            errors: {
                type: Object,
                default() {
                    return {};
                }
            },
            errorField: {
                type: String,
                default: ''
            }
        },
        computed: {
            hasError() {
                return _.has(this.errors, this.errorField)
            },
            fieldErrors() {
                return _.get(this.errors, this.errorField)
            }
        }
    }
</script>

<style scoped>

</style>