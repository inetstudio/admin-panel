window.Admin.vue.mixins['errors'] = {
    props: {
        errors: {
            type: Object,
            default() {
                return {};
            }
        },
        errorField: {
            type: String,
            default: ''
        }
    },
    computed: {
        hasError() {
            return _.has(this.errors, this.errorField)
        },
        fieldErrors() {
            return _.get(this.errors, this.errorField)
        }
    }
};
