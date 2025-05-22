<template>
    <PanelItem :index="index" :field="field">
        <template #value>
            <div v-if="field.value.length > 0">
                <TagList
                    :tags="field.value"
                    :resource-name="field.resourceName"
                    :editable="false"
                    :with-preview="field.withPreview"
                />
            </div>
        </template>
    </PanelItem>
</template>

<script>
export default {
    props: ["index", "resource", "resourceName", "resourceId", "field"],
};
</script>
