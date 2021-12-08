export default {
  data() {
    return {
      state: {
        loading: true,
        ready: false
      }
    };
  },
  computed: {
    isReady() {
      let component = this;

      return component.state.ready;
    },
    isLoading() {
      let component = this;

      return component.state.loading;
    }
  },
  methods: {
    startLoading() {
      let component = this;

      component.state.loading = true;

      component.$emit('start-loading');
    },
    stopLoading() {
      let component = this;

      component.state.loading = false;

      component.$emit('stop-loading');
    },
    ready() {
      let component = this;

      component.state.ready = true;

      component.$emit('component-ready');
    },
    notReady() {
      let component = this;

      component.state.ready = false;

      component.$emit('component-not-ready');
    }
  }
}
