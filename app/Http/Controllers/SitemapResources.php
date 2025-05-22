<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Industry;
use App\Models\Story;
use App\Models\Project;
use App\Models\Page;

class SitemapResources extends Controller
{
    public function getDynamicUrls(Request $request)
    {
        $services = Service::active()->pluck('slug')->map(fn ($slug) => "/services/$slug");
        $industries = Industry::active()->pluck('slug')->map(fn ($slug) => "/industries/$slug");
        $stories = Story::active()->pluck('slug')->map(fn ($slug) => "/stories/$slug");
        $projects = Project::active()->pluck('slug')->map(fn ($slug) => "/projects/$slug");
        $pages = Page::active()->pluck('slug')->map(fn ($slug) => "/pages/$slug");

        return [
            ...$pages,
            ...$projects,
            ...$industries,
            ...$services,
            ...$stories,
        ];
    }
}
