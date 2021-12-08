const path = require('path');

module.exports = {
  resolve: {
    alias: {
      '~admin-panel': path.resolve(__dirname, 'vue/'),
    }
  }
};
