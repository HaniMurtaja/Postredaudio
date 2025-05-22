<template>
    <DefaultField
        :field="field"
        :errors="errors"
        :show-help-text="showHelpText"
        :full-width-content="fullWidthContent"
    >
        <template
            #field
            class="mt-1 md:mt-0 pb-5 px-6 md:px-8 md:w-3/5 w-full md:py-5"
        >
            <div class="module-element-wrapper">
                <div class="module-element-container">
                    <div
                        v-for="(singleModule, index) in field.modules"
                        :class="[
                            'module-element',
                            { active: singleModule['active'] },
                        ]"
                        :data-index="index"
                        :draggable="singleModule['active']"
                        @dragstart="dragStart"
                        @dragover="dragOver"
                        @drop="dragDrop"
                        @click="toggleModuleStatus(index)"
                    >
                        <div class="module-name">
                            {{ singleModule["name"].replaceAll("_", " ") }}
                        </div>
                        <div class="module-status"></div>
                    </div>
                </div>
            </div>
        </template>
    </DefaultField>
</template>

<script>
import { FormField, HandlesValidationErrors } from "laravel-nova";
import { ref } from "vue";

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ["resourceName", "resourceId", "field"],

    setup() {
        const dragElementSrc = ref("");

        return {
            dragElementSrc,
        };
    },

    methods: {
        fill(formData) {
            formData.append(
                this.field.attribute,
                JSON.stringify(
                    this.field.modules
                        .filter((module) => module.active)
                        .map((module) => {
                            return {
                                id: module["id"],
                                sort_order: module["sort_order"],
                            };
                        })
                )
            );
        },

        toggleModuleStatus(index) {
            // Prevent disabling last module
            if (
                !(
                    this.field.modules[index]["active"] &&
                    this.field.modules.filter(
                        (module) => module.active === true
                    ).length <= 1
                )
            ) {
                this.field.modules[index]["active"] =
                    !this.field.modules[index]["active"];
            }
        },

        dragStart(e) {
            const moduleElement = e.target.closest(".module-element");
            e.dataTransfer.effectAllowed = "move";
            e.dataTransfer.setData("text/html", moduleElement.innerHTML);

            this.dragElementSrc = moduleElement;
        },

        dragOver(e) {
            e.preventDefault();
        },

        dragDrop(e) {
            const destinationElement = e.target.closest(".module-element");
            const draggedElement = this.dragElementSrc;

            if (
                draggedElement !== destinationElement &&
                destinationElement.getAttribute("draggable") === "true"
            ) {
                const fromIndex = draggedElement.dataset.index;
                const toIndex = destinationElement.dataset.index;
                const element = this.field.modules[fromIndex];

                this.field.modules.splice(fromIndex, 1);
                this.field.modules.splice(toIndex, 0, element);

                this.field.modules.map((module, index) => {
                    module.sort_order = index + 1;

                    return module;
                });
            }
        },
    },
};
</script>

<style></style>
