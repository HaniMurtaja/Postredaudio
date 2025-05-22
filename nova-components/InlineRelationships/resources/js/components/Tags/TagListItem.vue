<template>
    <button
        type="button"
        @click.stop="handleClick"
        class="list-item block w-full flex items-center text-left rounded px-1 py-1"
        :class="{
            'hover:bg-gray-50 dark:hover:bg-gray-700': withPreview,
            '!cursor-default': !withPreview,
        }"
    >
        <div class="drag-box"><div class="icon-container">↕</div></div>
        <div class="item-title flex items-center space-x-3">
            <Avatar
                v-if="tag.avatar"
                :src="tag.avatar"
                :rounded="true"
                medium
            />

            <div>
                <p class="text-xs font-semibold">{{ tag.display }}</p>
                <p class="text-xs" v-if="withSubtitles">{{ tag.subtitle }}</p>
            </div>
        </div>

        <IconButton
            iconType="minus-circle"
            v-if="editable"
            @click.stop="$emit('tag-removed', index)"
            type="button"
            tabindex="0"
            class="ml-auto flex appearance-none cursor-pointer text-red-500 hover:text-red-600 active:outline-none"
            :title="__('Delete')"
        >
            <Icon type="minus-circle" />
        </IconButton>

        <PreviewResourceModal
            v-if="withPreview"
            @close="handleClick"
            :show="shown"
            :resource-id="tag.value"
            :resource-name="resourceName"
        />
    </button>
</template>

<script setup>
import { ref } from "vue";
import PreviewResourceModal from "../Modals/PreviewResourceModal.vue";

const shown = ref(false);

const props = defineProps({
    resourceName: { type: String },
    index: { type: Number, required: true },
    tag: { type: Object, required: true },
    editable: { type: Boolean, default: true },
    withSubtitles: { type: Boolean, default: true },
    withPreview: { type: Boolean, default: false },
});

defineEmits(["tag-removed", "click"]);

function handleClick() {
    if (props.withPreview) {
        shown.value = !shown.value;
    }
}
</script>

<style>
.list-item {
}
.list-item .drag-box {
    width: 10px;
    padding-right: 10px;
}
.list-item .drag-box .icon-container {
    display: none;
}

.list-item .item-title {
    padding-top: 2px;
}

.list-item:hover .drag-box .icon-container {
    display: block;
}
</style>
