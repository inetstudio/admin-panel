import hash from 'object-hash';

export default {
  data: function () {
    return {
      item: {
        model: {},
        hash: '',
        isModified: false
      }
    };
  },
  watch: {
    'item.model': {
      handler: function(newValue) {
        let component = this;

        component.item.hash = hash(newValue);
      },
      deep: true,
      immediate: false
    },
    'item.hash': {
      handler: function(newValue, oldValue) {
        let component = this;

        if (oldValue === '') {
          return;
        }

        component.item.isModified = (newValue !== oldValue);
      }
    }
  },
  methods: {
    setModel(data) {
      let component = this;

      if (_.has(data, 'model')) {
        component.item = data;
      } else {
        component.item.model = data;
      }
    }
  }
};
