<?php

namespace App\Observers;

use App\Models\Page;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class PageObserver
{
    public function creating(Page $page)
    {
        if($page->page_type != 2){
            $page->slug = $this->generateSlug($page->name);
        }
        if(!$page->hash){
            $page->hash=Str::random(16);
        }
    }

    public function updating(Page $page)
    {
        if(($page->isDirty('name') && $page->page_type != 2) || ($page->isDirty('page_type') && $page->page_type != 2)) {
            $page->slug = $this->generateSlug($page->name);
            $page->external_link = null;
            if ($page->page_type == 1) {
                $page->project_section_label = null;
            }
        }else if($page->page_type == 2) {
            $page->slug = null;
            $page->project_section_label = null;
            $page->contentBlocks()->update(['resource_id' => null, 'resource_type' => null]);
        }
    }

    public function updated(Page $page)
    {
        $this->deleteCache($page);
    }

    public function created(Page $page)
    {
        $this->deleteCache($page);
    }

    public function deleting(Page $page)
    {
        $page->contentBlocks()->update(['resource_id' => null, 'resource_type' => null]);
    }

    public function deleted(Page $page)
    {
        $this->deleteCache($page);
    }

    public function deleteCache(Page $page)
    {
        $pageHashes = Page::pluck('hash')->toArray();
        Cache::forget("pages");
        Cache::forget("pages-all");
        foreach($pageHashes as $hash){
            Cache::forget("pages-".$hash);
        }
        if ($page->wasChanged('slug')) {
            Cache::forget("page-" . $page->getOriginal('slug'));
            Cache::forget("page-" . $page->getOriginal('slug') . "-all");
            foreach($pageHashes as $hash){
                Cache::forget("page-".$page->getOriginal('slug')."-".$hash);
            }
        } else {
            Cache::forget("page-$page->slug");
            Cache::forget("page-$page->slug-all");
            foreach($pageHashes as $hash){
                Cache::forget("page-".$page->slug."-".$hash);
            }
        }
    }

    private function generateSlug($string)
    {
        return rtrim(urlencode(preg_replace('/\W+/', '-', strtolower(trim($string)))), "-");
    }
}
