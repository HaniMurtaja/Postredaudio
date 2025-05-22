<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use Illuminate\Support\Facades\Blade;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use App\Nova\ApprovedIp;
use App\Nova\Achievement;
use App\Nova\Cast;
use App\Nova\Client;
use App\Nova\ContentBlock;
use App\Nova\Department;
use App\Nova\Industry;
use App\Nova\Page;
use App\Nova\Project;
use App\Nova\User;
use App\Nova\Service;
use App\Nova\Story;
use App\Nova\TeamMember;
use App\Nova\Testimonial;
use App\Nova\Vacancy;
use App\Models\User as UserModel;
use App\Nova\HomeGalleryItem;
use App\Nova\Dashboards\Home;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Boolean;
use App\Nova\Flexible\FlexibleExtended;
use App\Nova\Flexible\Layouts\ContentBlocks\Misc\LinkItem;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\BooleanGroup;
use Laravel\Nova\Panel;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    public function boot()
    {
        Number::macro('defaultValue', function ($default) {
            return $this->resolveUsing(function ($value) use ($default) {
                return $value ?? $default;
            });
        });
        BooleanGroup::macro('defaultIndustries', function ($default) {
            return $this->resolveUsing(function ($value) use ($default) {
                return $value ?? $default;
            });
        });

        parent::boot();

        Nova::withoutNotificationCenter();
        Nova::withoutGlobalSearch();
        Nova::withoutThemeSwitcher();
        Nova::footer(function ($request) {
            return Blade::render('<div style="text-align:center">© ' . date('Y') . ' HOLYMOTORS</div>');
        });

        \Outl1ne\NovaSettings\NovaSettings::addSettingsFields([
            Text::make('Footer Text', 'footer'),
            FlexibleExtended::make('Social Links', 'social_links')
                ->collapsed()
                ->addSingleLayout(LinkItem::class)
                ->button('Add Link Item'),
            Panel::make('Contact Page', [
                Text::make('Header 1', 'contact_h1'),
                Text::make('Header 2', 'contact_h2'),
                Text::make('Email', 'contact_email'),
                Boolean::make('Work With Our Team', 'contact_show_team_section'),
            ]),
            Panel::make('Contact Page Form', [
                Text::make('First Field Title', 'contact_form_field_1_text'),
                FlexibleExtended::make('First Field Options', 'contact_form_field_1_options')
                    ->collapsed()
                    ->addLayout('Option', 'option', [
                        Text::make('Text'),
                    ])
                    ->button('Add Option'),
                Text::make('Second Field Title', 'contact_form_field_2_text'),
                FlexibleExtended::make('Second Field Options', 'contact_form_field_2_options')
                    ->collapsed()
                    ->addLayout('Option', 'option', [
                        Text::make('Text'),
                    ])
                    ->button('Add Option'),
            ])
        ], [
            'contact_show_team_section' => 'boolean',
        ]);

        Nova::mainMenu(function (Request $request) {
            return [
                MenuSection::dashboard(Home::class)->icon('home'),

                MenuSection::make('Content', [
                    MenuItem::resource(Achievement::class),
                    MenuItem::resource(Client::class),
                    MenuItem::resource(ContentBlock::class),
                    MenuItem::resource(Department::class),
                    MenuItem::resource(HomeGalleryItem::class)
                        ->tooltip('Set the order of featured items in the main gallery slider.'),
                    MenuItem::resource(Industry::class),
                    MenuItem::resource(Page::class),
                    MenuItem::resource(Project::class),
                    MenuItem::resource(Cast::class)
                        ->tooltip('Misc info about the project, like cast members.'),
                    MenuItem::resource(Service::class),
                    MenuItem::resource(Story::class),
                    MenuItem::resource(TeamMember::class),
                    MenuItem::resource(Testimonial::class),
                    MenuItem::resource(Vacancy::class),
                    MenuItem::make('Other')->path('/misc-options/general'),
                ])->icon('collection'),

                MenuSection::make('Misc', [
                    MenuItem::resource(User::class),
                    MenuItem::resource(ApprovedIp::class),
                ])->icon('puzzle')->collapsable()->collapsedByDefault(),
            ];
        });
    }

    protected function routes()
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return in_array($user->email, UserModel::pluck('email')->toArray());
        });
    }

    protected function dashboards()
    {
        return [
            new \App\Nova\Dashboards\Home,
        ];
    }

    public function tools()
    {
        return [
            new \Outl1ne\NovaSettings\NovaSettings
        ];
    }

    public function register()
    {
        Nova::initialPath('/dashboards/home');
        // Prevent last update time comparison
        $this->app->bind('Laravel\Nova\Http\Controllers\ResourceUpdateController', 'App\Http\Controllers\Nova\ResourceUpdateController');
    }
}
