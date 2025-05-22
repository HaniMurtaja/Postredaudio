<template>
    <DefaultField
        :field="currentField"
        :errors="errors"
        :show-help-text="showHelpText"
        :full-width-content="fullWidthContent"
    >
        <template #field class="inline-relationship-field">
            <div class="space-y-4">
                <div class="flex items-center space-x-2">
                    <SearchSearchInput
                        ref="searchable"
                        :data-testid="`${field.resourceName}-search-input`"
                        @input="performSearch"
                        :error="hasError"
                        :debounce="field.debounce"
                        :options="availableResources"
                        @selected="selectResource"
                        trackBy="value"
                        :disabled="currentlyIsReadonly"
                        :loading="loading"
                        class="w-full search-input-field"
                    >
                        <template #option="{ selected, option }">
                            <SearchInputResult
                                :option="option"
                                :selected="selected"
                                :with-subtitles="field.withSubtitles"
                            />
                        </template>
                    </SearchSearchInput>

                    <CreateRelationButton
                        v-if="field.showCreateRelationButton"
                        v-tooltip="
                            __('Create :resource', {
                                resource: field.singularLabel,
                            })
                        "
                        @click="openRelationModal"
                        :dusk="`${field.attribute}-inline-create`"
                        tabindex="0"
                    />
                </div>

                <div
                    v-if="value.length > 0"
                    :dusk="`${field.attribute}-selected-tags`"
                >
                    <TagList
                        :tags="value"
                        class="test-tag-list"
                        @tag-removed="(i) => removeResource(i)"
                        @changeSortOrder="updateSortOrder(value)"
                        :resource-name="field.resourceName"
                        :editable="!currentlyIsReadonly"
                        :with-preview="field.withPreview"
                    />
                </div>
            </div>

            <CreateRelationModal
                :resource-name="field.resourceName"
                :show="field.showCreateRelationButton && relationModalOpen"
                :size="field.modalSize"
                @set-resource="handleSetResource"
                @create-cancelled="relationModalOpen = false"
            />
        </template>
    </DefaultField>
</template>

<script>
import DependentFormField from "../mixins/DependentFormField.js";
import PerformsSearches from "../mixins/PerformsSearches.js";
import HandlesValidationErrors from "../mixins/HandlesValidationErrors.js";
import { mapProps } from "../mixins/propTypes.js";
import storage from "../storage/ResourceStorage";
import TagList from "../components/Tags/TagList";
import SearchInputResult from "../components/Inputs/SearchInputResult";
import PreviewResourceModal from "../components/Modals/PreviewResourceModal";

export default {
    components: { PreviewResourceModal, SearchInputResult, TagList },
    mixins: [DependentFormField, PerformsSearches, HandlesValidationErrors],

    props: {
        ...mapProps([
            "resourceId",
            "relationship",
            "disablePagination",
            "attachableResources",
            "attachableResourceName",
        ]),
    },

    data: () => ({
        relationModalOpen: false,
        search: "",
        value: [],
        loading: false,
        availableResources: [],
    }),

    mounted() {
        this.value.sort(function (a, b) {
            return a.sort_order - b.sort_order;
        });

        this.availableResources = this.field.attachableResources;
    },

    methods: {
        /**
         * Filter available attachable resources
         */
        performSearch(search) {
            if (search.length) {
                this.availableResources = this.field.attachableResources.filter(
                    (resource) =>
                        resource.display
                            .toLowerCase()
                            .includes(search.toLowerCase())
                );
            } else {
                this.availableResources = this.field.attachableResources;
            }
        },

        fill(formData) {
            this.fillIfVisible(
                formData,
                this.currentField.attribute,
                this.value.length > 0 ? JSON.stringify(this.value) : ""
            );
        },

        selectResource(resource) {
            const alreadySelected = this.value.filter(
                (tag) => tag.value === resource.value
            );

            if (alreadySelected.length === 0) {
                // Set new element's sort order
                resource.sort_order = this.value.length
                    ? this.value.reduce((prev, cur) => {
                          return prev.sort_order > cur.sort_order ? prev : cur;
                      }).sort_order + 1
                    : 1;

                this.value.push(resource);

                // Remove resource from search list
                const index = this.field.attachableResources.indexOf(resource);

                if (index > -1) {
                    this.field.attachableResources.splice(index, 1);
                }
            }
        },

        // Get created resource's display name and add it to the tag list
        handleSetResource(resource) {
            storage
                .getNewResourceDisplayName(
                    this.field.attachableResourceName,
                    resource.id
                )
                .then(({ data }) => {
                    this.selectResource(data);
                    this.closeRelationModal();
                });
        },

        removeResource(index) {
            const removedTag = this.value[index];

            this.value.splice(index, 1);
            this.value.map((tag) => {
                if (tag.sort_order > removedTag.sort_order) tag.sort_order--;

                return tag;
            });

            this.field.attachableResources.push(removedTag);
        },

        openRelationModal() {
            this.relationModalOpen = true;
        },

        closeRelationModal() {
            this.relationModalOpen = false;
        },

        updateSortOrder(tags) {},
    },
};
</script>
