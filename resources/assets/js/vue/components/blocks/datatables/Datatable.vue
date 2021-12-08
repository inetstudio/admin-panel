<template>
  <div class="table-responsive">
    <table class="table table-striped table-bordered table-hover dataTable" v-bind="attributes" ref="table">
      <thead>
        <tr>
          <th v-for="column in options.columns" :key="column.name" :title="column.title">{{ column.title || '' }}</th>
        </tr>
      </thead>
      <tbody class="dtBody">
      </tbody>
      <tbody>
        <tr v-if="rows.length === 0" class="odd"><td colspan="7" class="dataTables_empty">Записи отсутствуют.</td></tr>
        <table-row v-for="row in rows" :key="row.id" v-bind:rowProp="row" v-bind:events="events" v-on:component-event="eventDispatcher" />
      </tbody>
    </table>
  </div>
</template>

<script>
import { v4 as uuidv4 } from 'uuid';

export default {
  name: 'Datatable',
  components: {
    'table-row': () => import('./TableRow.vue')
  },
  props: {
    attributesProp: {
      type: Object,
      default() {
        return {};
      }
    },
    optionsProp: {
      type: Object,
      default() {
        return {};
      }
    },
    eventsProp: {
      type: Object,
      default() {
        return {};
      }
    }
  },
  data() {
    let component = this;

    return {
      attributes: _.cloneDeep(component.attributesProp),
      options: _.cloneDeep(component.optionsProp),
      table: null,
      rows: [],
      events: _.merge({
        table: {
          itemDestroyed: function (componentArg, data) {
            componentArg.table.ajax.reload(null, false);
          },
          itemUpdated: function (componentArg, data) {
            componentArg.table.ajax.reload(null, false);
          }
        },
        row: {},
        td: {}
      }, component.eventsProp)
    };
  },
  methods: {
    eventDispatcher(payload) {
      let component = this;

      if (component.events.table.hasOwnProperty(payload.event)) {
        component.events.table[payload.event](component, payload.data);
      }
    },
    getAttributes(element) {
      let attributes = {};

      if (element.hasAttributes()) {
        let attrs = element.attributes;

        for(let i = 0; i < attrs.length; i++) {
          attributes[attrs[i].name] = attrs[i].value;
        }
      }

      return attributes;
    },
    getRowTds(element) {
      let component = this;

      let tds = [];

      let childNodes = element.childNodes;

      for (let i = 0; i < childNodes.length; i++) {
        let td = {
          id: uuidv4(),
          attributes: component.getAttributes(childNodes[i]),
          data: {
            type: (childNodes[i].classList.contains('vue-component')) ? 'vue-component' : 'html',
            value: (childNodes[i].classList.contains('vue-component')) ? component.getComponentInfo(childNodes[i]) : childNodes[i].innerHTML,
          }
        };

        tds.push(td);
      }

      return tds;
    },
    getComponentInfo(element) {
      let componentData = JSON.parse(element.textContent.trim());

      return {
        component: componentData['component'],
        props: componentData['props']
      }
    },
    update() {
      let component = this;

      component.table.ajax.reload(null, false)
    }
  },
  mounted: function() {
    let component = this;

    component.options.rowCallback = function(dtRow, data) {
      let row = {
        id: uuidv4(),
        attributes: component.getAttributes(dtRow),
        tds: component.getRowTds(dtRow)
      };

      component.rows.push(row);
    }

    component.options.preDrawCallback = function(settings) {
      component.rows = [];
    }

    component.table = $(component.$refs.table).DataTable(component.options);
  }
};
</script>

<style scoped>
  .dtBody {
    display:none;
  }
</style>
