<?php

namespace App\Observers;

use App\Models\Service;
use Illuminate\Support\Facades\Cache;

class ServiceObserver
{
    public function creating(Service $service)
    {
        $this->setSlug($service);
    }

    public function updating(Service $service)
    {
        if ($service->isDirty('name')) $this->setSlug($service);
    }

    public function updated(Service $service)
    {
        $this->deleteCache($service);
    }

    public function created(Service $service)
    {
        $this->deleteCache($service);
    }

    public function deleting(Service $service)
    {
        $service->contentBlocks()->update(['resource_id' => null, 'resource_type' => null]);
    }

    public function deleted(Service $service)
    {
        Cache::forget("resources");

        $this->deleteCache($service);
    }

    public function deleteCache(Service $service)
    {
        Cache::forget("resources");
        Cache::forget("resources-all");
        Cache::forget("services");
        Cache::forget("services-all");

        if ($service->wasChanged('slug')) {
            Cache::forget("service-" . $service->getOriginal('slug'));
            Cache::forget("service-" . $service->getOriginal('slug') . "-all");
        } else {
            Cache::forget("service-$service->slug");
            Cache::forget("service-$service->slug-all");
        }
    }

    private function setSlug(Service $service)
    {
        $service->slug = rtrim(urlencode(preg_replace('/\W+/', '-', strtolower(trim($service->name)))), "-");
    }
}
