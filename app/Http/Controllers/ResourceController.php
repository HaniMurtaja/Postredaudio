<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Project\ProjectResource;
use App\Http\Resources\Project\SingleProjectResource;
use App\Http\Resources\Story\StoryResource;
use App\Http\Resources\Story\SingleStoryResource;
use App\Http\Resources\Service\SingleServiceResource;
use App\Http\Resources\Service\ServiceResource;
use App\Http\Resources\Industry\SingleIndustryResource;
use App\Http\Resources\Industry\IndustryResource;
use App\Http\Resources\VacancyResource;
use App\Http\Resources\Page\PageResource;
use App\Http\Resources\Page\SinglePageResource;
use App\Http\Resources\HomeGalleryResource;
use App\Http\Resources\ContactResource;
use App\Models\Project;
use App\Models\Story;
use App\Models\Industry;
use App\Models\Service;
use App\Models\Vacancy;
use App\Models\Page;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use App\Enums\CountableResource;
use App\Models\HomeGalleryItem;

class ResourceController extends Controller
{
    private $draft;
    private $hash;
    private $showAll;
    private $showSelected;

    public function __construct(Request $request){
        $this->draft = $request->get('d') === '1' ?? false;
        $this->hash = $request->get('h') && strlen($request->get('h')) <= 16 ? $request->get('h') : null;
        $this->showAll = $this->draft ? '-all' : '';
        $this->showSelected = !$this->draft && $this->hash ? '-'.$this->hash : '';
    }

    public function gallery()
    {
        return Cache::store('file')->remember('gallery' . $this->showAll, config('cache.cache_time'), fn()=>HomeGalleryResource::collection(HomeGalleryItem::gallery($this->draft)));
    }

    public function projects()
    {
        return Cache::store('file')->remember('projects' . $this->showAll, config('cache.cache_time'), fn()=>ProjectResource::collection(Project::projects($this->draft)));
    }

    public function project(string $slug)
    {
        return Cache::store('file')->remember("project-$slug" . $this->showAll, config('cache.cache_time'), function() use($slug) {
            return new SingleProjectResource(Project::project($slug, $this->draft));
        });
    }

    public function stories(Request $request)
    {
        $page = $request->page ?? "1";
        
        return Cache::store('file')->remember("stories-$page" . $this->showAll, config('cache.cache_time'), fn()=>StoryResource::collection(Story::stories($this->draft)));
    }

    public function story(string $slug){
        return Cache::store('file')->remember("story-$slug" . $this->showAll, config('cache.cache_time'), function() use($slug){
            return new SingleStoryResource(Story::story($slug, $this->draft));
        });
    }

    public function services()
    {
        return Cache::store('file')->remember("services" .  $this->showAll, config('cache.cache_time'), fn()=> ServiceResource::collection(Service::services($this->draft)));
    }

    public function service(string $slug)
    {
        return Cache::store('file')->remember("service-$slug" . $this->showAll, config('cache.cache_time'), function() use($slug) {
            return new SingleServiceResource(Service::service($slug, $this->draft));
        });
    }

    public function industries()
    {
        return Cache::store('file')->remember("industries" . $this->showAll, config('cache.cache_time'), fn()=>IndustryResource::collection(Industry::industries($this->draft)));
    }

    public function industry(string $slug){
        return Cache::store('file')->remember("industry-$slug" . $this->showAll, config('cache.cache_time'), function() use($slug){
            return new SingleIndustryResource(Industry::industry($slug, $this->draft));
        });
    }

    public function vacancies()
    {
        return Cache::store('file')->remember('vacancies' . $this->showAll, config('cache.cache_time'), fn()=>VacancyResource::collection(Vacancy::vacancies($this->draft)));
    }

    public function pages()
    {
        return Cache::store('file')->remember('pages' . $this->showAll . $this->showSelected, config('cache.cache_time'), fn()=>PageResource::collection(Page::pages($this->draft, $this->hash)));
    }

    public function page(string $slug){
        return Cache::store('file')->remember("page-$slug" . $this->showAll . $this->showSelected, config('cache.cache_time'), function() use($slug) {
            return new SinglePageResource(Page::page($slug, $this->draft, $this->hash));
        });
    }

    public function count()
    {
        return Cache::store('file')->remember('resources' . $this->showAll, config('cache.cache_time'), function () {
            return array_merge(...array_map(
                fn ($resource) => [class_basename($resource) => $resource::when(!$this->draft, fn (Builder $query) => $query->active())->count()],
                CountableResource::values()
            ));
        });
    }
    
    public function contact()
    {
        return new ContactResource(nova_get_settings());
    }
}
