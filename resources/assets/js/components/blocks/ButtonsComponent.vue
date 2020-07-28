<template>
    <div class="form-group row">
        <div class="col-sm-12">
            <input type="submit" value="Сохранить" class="btn btn-sm btn-primary" @click.prevent="save">
            <a v-if="back !== ''" :href="back" class="btn btn-sm btn-w-m btn-warning m-l-xs">Отменить</a>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'vue-block-buttons',
        props: {
            back: {
                type: String,
                default: ''
            }
        },
        methods: {
            save() {
                this.$emit('save');
            }
        }
    }
</script>
