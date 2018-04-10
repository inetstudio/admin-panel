<template>
    <div class="form-group">
        <div class="col-sm-12">
            <input type="submit" value="Сохранить" class="btn btn-primary" @click.prevent="save">
            <a :href="back" class="btn btn-w-m btn-warning m-l-xs" v-if="back !== ''">Отменить</a>
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
