<?php

namespace App\Observers;

use App\Models\ContentBlock;
use App\Models\Client;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Cache;

class ContentBlockObserver
{
    public function created(ContentBlock $contentBlock)
    {
        $this->setOrder($contentBlock);
        $this->validateHeaderLayoutCount($contentBlock);
        $this->attachDepartments($contentBlock);
        $this->attachClients($contentBlock);
        $this->attachTestimonials($contentBlock);
        $this->deleteCache($contentBlock);
    }

    public function updated(ContentBlock $contentBlock)
    {
        $this->setOrder($contentBlock);
        $this->validateHeaderLayoutCount($contentBlock);
        $this->attachDepartments($contentBlock);
        $this->attachClients($contentBlock);
        $this->attachTestimonials($contentBlock);
        $this->deleteCache($contentBlock);
    }

    public function deleted(ContentBlock $contentBlock)
    {
        $this->deleteCache($contentBlock);
    }

    private function deleteCache(ContentBlock $contentBlock)
    {
        $oldResourceModel = $contentBlock->getOriginal('resource_type');
        $newResourceModel = $contentBlock->resource_type;
        $oldId = (int) $contentBlock->getOriginal('resource_id');
        $newId = (int) $contentBlock->resource_id;

        if ($newId && $newResourceModel) {
            $newSlug = $newResourceModel::find($newId)->slug;
            Cache::forget(strtolower(class_basename($newResourceModel) . "-$newSlug"));
            Cache::forget(strtolower(class_basename($newResourceModel) . "-$newSlug-all"));

            if ($oldId && $oldResourceModel && ($newId !== $oldId || $newResourceModel !== $oldResourceModel)) {
                $oldSlug = $oldResourceModel::find($oldId)->slug;
                Cache::forget(strtolower(class_basename($oldResourceModel) . "-$oldSlug"));
                Cache::forget(strtolower(class_basename($oldResourceModel) . "-$oldSlug-all"));
            }
        }
    }

    private function attachTestimonials(ContentBlock $contentBlock)
    {
        $flexibleContent = $contentBlock->content instanceof \Whitecube\NovaFlexibleContent\Layouts\Collection ? $contentBlock->content : $contentBlock->toFlexible($contentBlock->content);

        if ($flexibleContent && $flexibleContent->count() && $flexibleContent->first()->name() === 'testimonial') {
            $contentBlock->testimonials()->detach();
            $testimonialsArray = json_decode(json_encode($flexibleContent->first()->getAttribute('testimonials')), true);
            foreach ($testimonialsArray as $index => $testimonial) {
                $contentBlock->testimonials()->attach($testimonial['attributes']['testimonial'], ['sort_order' => $index + 1]);
            }
        } else if ($contentBlock->testimonials()->count()) {
            $contentBlock->testimonials()->detach();
        }
    }

    private function attachClients(ContentBlock $contentBlock)
    {
        $flexibleContent = $contentBlock->content instanceof \Whitecube\NovaFlexibleContent\Layouts\Collection ? $contentBlock->content : $contentBlock->toFlexible($contentBlock->content);

        if ($flexibleContent && $flexibleContent->count() && $flexibleContent->first()->name() === 'client') {
            $industries = array_keys(array_filter((array) $flexibleContent->first()->getAttribute('industries'), fn ($industry) => $industry));
            if (count($industries)) {
                $contentBlock->clients()->sync(Client::whereIn('industry_id', $industries)->get()->pluck('id'));
                return;
            }
        }

        if ($contentBlock->clients()->count()) {
            $contentBlock->clients()->detach();
        }
    }

    private function attachDepartments(ContentBlock $contentBlock)
    {
        $flexibleContent = $contentBlock->content instanceof \Whitecube\NovaFlexibleContent\Layouts\Collection ? $contentBlock->content : $contentBlock->toFlexible($contentBlock->content);

        if ($flexibleContent && $flexibleContent->count() && $flexibleContent->first()->name() === 'department') {
            $contentBlock->departments()->detach();
            $departmentContentBlock = $flexibleContent->first()->getAttributes()['departments'];
            $departments = $departmentContentBlock ? json_decode(json_encode($departmentContentBlock), true) : [];

            foreach ($departments as $index => $department) {
                $contentBlock->departments()->attach($department['attributes']['department'], ['sort_order' => $index + 1]);
            }
        } else if ($contentBlock->departments()->count()) {
            $contentBlock->departments()->detach();
        }
    }

    private function setOrder(ContentBlock $contentBlock)
    {
        if (
            $contentBlock->resource &&
            (
                ($contentBlock->wasChanged('resource_id') || $contentBlock->wasChanged('resource_type')) ||
                !$contentBlock->sort_order
            )
        ) {
            $contentBlock->updateQuietly(['sort_order' => null]);
            $latestOrder = $contentBlock->resource->contentBlocks()->pluck('sort_order')->last();
            $contentBlock->updateQuietly(['sort_order' => $latestOrder + 1]);
        }
    }

    private function validateHeaderLayoutCount(ContentBlock $contentBlock)
    {
        if ($contentBlock->content) {
            $flexibleFieldConent = $contentBlock->toFlexible($contentBlock->content)->first();

            if ($flexibleFieldConent && $flexibleFieldConent->name() === 'header' && $contentBlock->resource) {
                $siblingContentBlocks = $contentBlock->resource->contentBlocks->where('id', '!=', $contentBlock->id);

                if ($siblingContentBlocks->count()) {
                    foreach ($siblingContentBlocks as $contentBlock) {
                        if ($contentBlock->content && $contentBlock->toFlexible($contentBlock->content)->first()) {
                            if ($contentBlock->toFlexible($contentBlock->content)->first()->name() === 'header') {
                                throw new \Exception('Header Layout Already Exists!');
                            }
                        }
                    }
                }
            }
        }
    }
}
