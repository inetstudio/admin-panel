<template>
    <div>
        <div class="form-group row" :class="{'has-error': hasError}" ref="date">
            <label class="col-sm-2 col-form-label">{{ label }}</label>
            <div class="col-sm-10 form-inline">
                <div class="input-group m-b" v-for="(date, index) in dates" :key="date.name">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <flat-pickr
                        v-model="date.value"
                        v-bind:config="config"
                        v-bind:id="date.name"
                        v-bind:name="date.name"
                        v-on:on-change="onChange"
                        class="form-control"
                        autocomplete="off"
                    >
                    </flat-pickr>
                    <div class="input-group-append">
                        <button class="btn btn-default" type="button" title="Очистить" data-clear=""><i class="fa fa-times"></i></button>
                    </div>
                    <span v-if="index !== (dates.length - 1)" class="input-group-addon"> - </span>
                </div>

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
  import flatPickr from 'vue-flatpickr-component';
  import {Russian as ruLocale} from 'flatpickr/dist/l10n/ru.js';

  export default {
    name: 'BaseDate',
    props: {
      label: {
        type: String,
        default: ''
      },
      dates: {
        type: Array,
        default() {
          return [
            {
              name: 'date',
              value: new Date()
            }
          ];
        }
      },
      options: {
        type: Object,
        default() {
          return {};
        }
      }
    },
    data() {
      return {
        config: _.merge(
            {
              wrap: true,
              static: true,
              dateFormat: 'd.m.Y H:i',
              locale: ruLocale
            },
            this.options
        )
      }
    },
    components: {
      flatPickr
    },
    methods: {
      onChange(selectedDates, dateStr, instance) {
        let component = this;

        component.$emit('update:'+instance.input.name, dateStr);
      }
    },
    mixins: [
      window.Admin.vue.mixins['errors']
    ]
  }
</script>

<style scoped>
    .form-control:disabled, .form-control[readonly]
    {
        background-color: #ffffff;
    }
</style>
