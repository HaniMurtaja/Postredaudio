<?php

namespace App\Observers;

use App\Models\Vacancy;
use Illuminate\Support\Facades\Cache;

class VacancyObserver
{
    public function creating(Vacancy $vacancy)
    {
        $this->updateSlug($vacancy);
    }

    public function updating(Vacancy $vacancy)
    {
        $this->updateSlug($vacancy);
    }

    public function updated(Vacancy $vacancy)
    {
        $this->deleteCache($vacancy);
    }

    public function created(Vacancy $vacancy)
    {
        $this->deleteCache($vacancy);
    }

    public function deleted(Vacancy $vacancy)
    {
        $this->deleteCache($vacancy);
    }

    public function deleteCache(Vacancy $vacancy)
    {
        Cache::forget("resources");
        Cache::forget("resources-all");
        Cache::forget("vacancies");
        Cache::forget("vacanciesa-all");
    }

    private function updateSlug(Vacancy $vacancy)
    {
        $vacancy->slug = rtrim(urlencode(preg_replace('/\W+/', '-', strtolower(trim($vacancy->title)))), "-");
    }
}
