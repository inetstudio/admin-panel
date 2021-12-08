<template>
    <td v-bind="tdProp.attributes" v-if="tdProp.data.type === 'html'" v-html="tdProp.data.value"></td>
    <td v-bind="tdProp.attributes" v-else-if="tdProp.data.type === 'vue-component'">
      <component :is="tdProp.data.value.component" v-bind="tdProp.data.value.props" v-on:component-event="eventDispatcher" />
    </td>
    <td v-else></td>
</template>

<script>
export default {
  name: 'RowTd',
  props: {
    tdProp: {
      type: Object,
      required: true
    },
    events: {
      type: Object,
      default() {
        return {
          td: {}
        };
      }
    }
  },
  methods: {
    eventDispatcher(payload) {
      let component = this;

      if (component.events.td.hasOwnProperty(payload.event)) {

        component.events.td[payload.event](component, payload.data);
      }

      component.$emit('component-event', payload);
    }
  }
};
</script>

<style scoped>

</style>
