<template>
    <PanelItem :index="index" :field="field" />
</template>

<script>
import methods from "../shared/methods.js";

export default {
    props: ["index", "resource", "resourceName", "resourceId", "field"],

    setup(props) {
        props.field.value = methods.capitalizeArrayElements(
            JSON.parse(props.field.value)
        );
    },
};
</script>
