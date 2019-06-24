<template>
    <div>
        <div class="form-group row" :class="{'has-error': hasError}" ref="date">
            <label :for="name" class="col-sm-2 col-form-label">{{ label }}</label>
            <div class="col-sm-10">
                <div class="input-group">
                    <div class="input-group m-b">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input :name="name[0]" type="text" :value="value[0]" :id="name[0]" class="form-control" v-bind="attributes" autocomplete="off">
                        <span class="input-group-addon"> - </span>
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input :name="name[1]" type="text" :value="value[1]" :id="name[1]" class="form-control" v-bind="attributes" autocomplete="off">
                    </div>
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
  export default {
    name: 'BaseDate',
    props: {
      label: {
        type: String,
        default: ''
      },
      name: {
        type: [String, Array],
        required: true
      },
      value: {
        type: [String, Array],
        required: true
      },
      attributes: {
        type: Object,
        default() {
          return {};
        }
      }
    },
    mounted() {
      let component = this;

      this.$nextTick(function() {
        let dateWrapper = $(component.$refs.date);

        dateWrapper.find('input').each(function () {
          let fieldName = $(this).attr('name');
          let fieldOptions = $(this).attr('data-options');
          let extOptions = (typeof fieldOptions === 'undefined') ? {} : JSON.parse(fieldOptions);

          let options = $.extend({
            locale: 'ru',
            allowInput: true,
            static: true,
            dateFormat: 'd.m.Y H:i',
            onChange: function(selectedDates, dateStr, instance) {
              component.$emit('update:'+fieldName, dateStr);
            }
          }, extOptions);

          $(this).flatpickr(options);
        });
      })
    },
    mixins: [
      window.Admin.vue.mixins['errors']
    ]
  }
</script>

<style scoped>
</style>
