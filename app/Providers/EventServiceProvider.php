<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Models\Story;
use App\Models\Industry;
use App\Models\Service;
use App\Models\Project;
use App\Models\Vacancy;
use App\Models\Page;
use App\Models\TeamMember;
use App\Models\ContentBlock;
use App\Models\Department;
use App\Models\Testimonial;
use App\Models\Achievement;
use App\Models\Cast;
use App\Models\HomeGalleryItem;
use App\Models\Client;
use App\Models\User;
use App\Models\Pivot\TeamMemberDepartment;
use App\Models\Pivot\Testimoniable;
use App\Models\Pivot\Projectable;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Observers\ContentBlockObserver;
use App\Observers\StoryObserver;
use App\Observers\VacancyObserver;
use App\Observers\IndustryObserver;
use App\Observers\ServiceObserver;
use App\Observers\TeamMemberObserver;
use App\Observers\AchievementObserver;
use App\Observers\ProjectObserver;
use App\Observers\PageObserver;
use App\Observers\UserObserver;
use App\Observers\TestimonialObserver;
use App\Observers\MediaObserver;
use App\Observers\DepartmentObserver;
use App\Observers\HomeGalleryItemObserver;
use App\Observers\ClientObserver;
use App\Observers\CastObserver;
use App\Observers\Pivot\TeamMemberDepartmentObserver;
use App\Observers\Pivot\TestimoniableObserver;
use App\Observers\Pivot\ProjectableObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    public function boot(): void
    {
        ContentBlock::observe(ContentBlockObserver::class);
        Project::observe(ProjectObserver::class);
        Story::observe(StoryObserver::class);
        Service::observe(ServiceObserver::class);
        Industry::observe(IndustryObserver::class);
        Page::observe(PageObserver::class);
        Vacancy::observe(VacancyObserver::class);
        Media::observe(MediaObserver::class);
        TeamMember::observe(TeamMemberObserver::class);
        Department::observe(DepartmentObserver::class);
        Achievement::observe(AchievementObserver::class);
        Client::observe(ClientObserver::class);
        Testimonial::observe(TestimonialObserver::class);
        TeamMemberDepartment::observe(TeamMemberDepartmentObserver::class);
        Testimoniable::observe(TestimoniableObserver::class);
        Cast::observe(CastObserver::class);
        HomeGalleryItem::observe(HomeGalleryItemObserver::class);
        User::observe(UserObserver::class);
        Projectable::observe(ProjectableObserver::class);
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
