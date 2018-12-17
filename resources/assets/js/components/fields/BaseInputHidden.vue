<template>
    <input type="hidden" :name="name" :value="value">
</template>

<script>
    export default {
        name: 'BaseInputHidden',
        props: {
            name: {
                type: String,
                required: true
            },
            value: [String, Number]
        }
    }
</script>
