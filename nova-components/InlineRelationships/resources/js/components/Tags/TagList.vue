<template>
    <div>
        <TagListItem
            v-for="(tag, index) in tags"
            class="tag-list-item"
            @tag-removed="(i) => $emit('tag-removed', i)"
            :index="index"
            :data-index="index"
            :data-id="tag.value"
            :data-order="tag.sort_order"
            :tag="tag"
            :resource-name="resourceName"
            :editable="editable"
            :with-subtitles="withSubtitles"
            :with-preview="withPreview"
            @dragstart="dragStart"
            @dragover="dragOver"
            @drop="dragDrop"
            :draggable="true"
        />
    </div>
</template>

<script setup>
import { ref } from "vue";
import TagListItem from "./TagListItem";

const emit = defineEmits(["inFocus", "submit", "changeSortOrder"]);
const props = defineProps({
    resourceName: { type: String },
    tags: { type: Array, default: [] },
    editable: { type: Boolean, default: true },
    withSubtitles: { type: Boolean, default: true },
    withPreview: { type: Boolean, default: false },
});

const dragElementSrc = ref("");

const dragStart = (e) => {
    const moduleElement = e.target.closest(".tag-list-item");
    e.dataTransfer.effectAllowed = "move";
    e.dataTransfer.setData("text/html", moduleElement.innerHTML);

    dragElementSrc.value = moduleElement;
};

const dragOver = (e) => {
    e.preventDefault();
};

const dragDrop = (e) => {
    const destinationElement = e.target.closest(".tag-list-item");
    const draggedElement = dragElementSrc.value;

    if (draggedElement !== destinationElement) {
        const fromIndex = draggedElement.dataset.index;
        const toIndex = destinationElement.dataset.index;
        const element = props.tags[fromIndex];

        props.tags.splice(fromIndex, 1);
        props.tags.splice(toIndex, 0, element);

        props.tags.map((tag, index) => {
            tag.sort_order = index + 1;

            return tag;
        });
    }

    emit("changeSortOrder");
};
</script>
