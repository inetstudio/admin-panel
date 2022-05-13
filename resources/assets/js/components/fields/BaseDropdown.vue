<template>
  <div>
    <div class="form-group row" :class="{'has-error': hasError}">
      <label class="col-sm-2 col-form-label font-bold">{{ label }}</label>
      <div class="col-sm-10">
        <v-select
            class="dropdown-style"
            v-bind="attributes"
            :options="preparedOptions"
            :value="preparedSelected"
            :selectable="option => !option.hasOwnProperty('group')"
            v-on="(source.url !== '') ? {search: onSearch} : {}"
            @input="onSelect"
            v-on:option:created="onCreated"
        >
          <div slot="no-options">Совпадений не найдено</div>
          <template #open-indicator="{ attributes }">
            <span v-bind="attributes">
                <b role="presentation"></b>
            </span>
          </template>
          <template v-slot:option="option">
            <div v-if="option.hasOwnProperty('group')" class="group">
              {{ option.group }}
            </div>
            <span v-html="option[attributes.label]" />
          </template>
          <template #selected-option="{ text }">
            <div style="display: flex; align-items: baseline">
              <span v-html="text" />
            </div>
          </template>
        </v-select>

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
import Swal from 'sweetalert2';
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';

export default {
  name: 'BaseDropdown',
  props: {
    label: {
      type: String,
      default: ''
    },
    options: {
      type: Array,
      default() {
        return [];
      }
    },
    selected: [Array, Number, String],
    attributes: {
      type: Object,
      default() {
        return {};
      }
    },
    showHr: {
      type: Boolean,
      default: true
    },
    source: {
      type: Object,
      default() {
        return {
          url: '',
          transformation: function (item) {
            return item;
          }
        }
      }
    }
  },
  data() {
    return {
      preparedOptions: [],
      preparedSelected: null
    };
  },
  watch: {
    options: {
      immediate: true,
      handler(newValues, oldValues) {
        let component = this;

        component.preparedOptions = newValues;
      }
    },
    selected: {
      immediate: true,
      handler(newValues, oldValues) {
        let component = this;

        component.preparedSelected = newValues;
      }
    }
  },
  components: {
    vSelect
  },
  methods: {
    onSelect(selected) {
      let component = this;

      component.$emit('update:selected', selected);
    },
    onCreated(created) {
      let component = this;

      component.$emit('update:selected', created.text);
    },
    onSearch(search, loading) {
      loading(true);

      this.search(loading, search, this);
    },
    search: _.debounce((loading, search, component) => {
      axios.post(component.source.url, {q: search})
          .then(response => {
            if (response.status !== 200) {
              throw new Error(response.statusText);
            }

            component.preparedOptions =  _.map(response.data.items, item => component.source.transformation(item));

            loading(false);
          })
          .catch(error => {
            Swal.fire({
              title: 'Ошибка',
              text: 'При загрузке результатов произошла ошибка',
              icon: 'error'
            });
          });
    }, 350)
  },
  mixins: [
    window.Admin.vue.mixins['errors']
  ]
}
</script>

<style>
.dropdown-style .vs__clear {
  padding-right: 10px;
}

.dropdown-style .vs__open-indicator {
  cursor: pointer;
}

.dropdown-style .vs__dropdown-option--disabled .group {
  font-weight: bold;
  margin-left: -10px;
  background: #ededed;
  color: #3b3b3b;
}

.dropdown-style .vs__dropdown-option--highlight {
  background: #1ab394;
  color: #fff
}

.dropdown-style .vs__open-indicator {
  height: 26px;
  position: absolute;
  top: 1px;
  right: 1px;
  width: 20px;
}

.dropdown-style .vs__open-indicator b {
  border-color: #888 transparent transparent;
  border-style: solid;
  border-width: 5px 4px 0;
  height: 0;
  left: 50%;
  margin-left: -4px;
  margin-top: -2px;
  position: absolute;
  top: 50%;
  width: 0;
}
</style>
