<template>
    <span>{{ fieldValue }}</span>
</template>

<script>
import methods from "../shared/methods.js";

export default {
    props: ["resourceName", "field"],

    computed: {
        fieldValue() {
            const mappedValue = methods.capitalizeArrayElements(
                JSON.parse(this.field.value)
            );
            return mappedValue;
        },
    },
};
</script>
