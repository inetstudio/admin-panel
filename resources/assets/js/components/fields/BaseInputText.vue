<template>
    <div>
        <div class="form-group row" :class="{'has-error': hasError}">
            <label :for="name" class="col-sm-2 col-form-label font-bold">{{ label }}</label>
            <div class="col-sm-10">
                <input :name="name" type="text" :value="value" :id="name" class="form-control" v-bind="attributes" @focus="$emit('focus')" @input="$emit('update:value', $event.target.value)" ref="field">

                <span class="form-text m-b-none"
                      v-for = "(error, index) in fieldErrors"
                      :key = index
                >{{ error}}</span>
            </div>
        </div>
        <div v-if="showHr" class="hr-line-dashed"></div>
    </div>
</template>

<script>
    import IMask from 'imask';

    export default {
        name: 'BaseInputText',
        props: {
            label: {
                type: String,
                default: ''
            },
            name: {
                type: String,
                required: true
            },
            showHr: {
                type: Boolean,
                default: true
            },
            value: [String, Number],
            attributes: {
                type: Object,
                default() {
                    return {};
                }
            },
            options: {
              type: Object,
              default() {
                return {};
              }
            }
        },
        mixins: [
            window.Admin.vue.mixins['errors']
        ],
        mounted() {
          let component = this;

          if (_.has(component.options, 'mask')) {
            IMask(component.$refs.field, component.options.mask);
          }
        }
    }
</script>

<style scoped>

</style>
