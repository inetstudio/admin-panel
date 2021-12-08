<template>
  <tr v-bind="rowProp.attributes">
    <row-td v-for="td in rowProp.tds" :key="td.id" v-bind:tdProp="td" v-bind:events="events" v-on:component-event="eventDispatcher" />
  </tr>
</template>

<script>
export default {
  name: 'TableRow',
  components: {
    'row-td': () => import('./RowTd.vue')
  },
  props: {
    rowProp: {
      type: Object,
      required: true
    },
    events: {
      type: Object,
      default() {
        return {
          row: {},
          td: {}
        };
      }
    }
  },
  methods: {
    eventDispatcher(payload) {
      let component = this;

      if (component.events.row.hasOwnProperty(payload.event)) {
        component.events.row[payload.event](component, payload.data);
      }

      component.$emit('component-event', payload);
    }
  }
};
</script>

<style scoped>

</style>
