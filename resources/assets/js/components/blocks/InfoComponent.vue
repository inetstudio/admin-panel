<template>
    <div>
        <div class="alert" :class="alertClass">
            {{ alert.message}}
        </div>
    </div>
</template>

<script>
    export default {
        name: 'vue-block-info',
        props: {
            alert: {
                type: Object,
                default() {
                    return {};
                }
            }
        },
        computed: {
            alertClass() {
                return 'alert-' + this.alert.type;
            }
        }
    }
</script>
