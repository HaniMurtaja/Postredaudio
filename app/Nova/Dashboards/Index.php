<?php

namespace App\Nova\Dashboards;

use Laravel\Nova\Dashboard;
use \NovaTextCard\NovaTextCard;

class Home extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            NovaTextCard::make()
                ->width('full')
                ->withMeta(['textAlign' => 'left'])
                ->content('<h1 class="text-3xl">POSTRED Admin Panel</h1>'),
            NovaTextCard::make()
                ->height('fixed')
                ->width('1/3')
                ->content('
                <div class="tip">Tip</div>
                <p class="dashboard">To view <i>all inactive</i> resources, use the following link:<br><a target="_blank" href="https://postredaudio.com?d=1">https://postredaudio.com<span style="color: red">?d=1</span></a>.</p>
                '),
            NovaTextCard::make()
                ->height('fixed')
                ->width('1/3')
                ->content('
                <div class="tip">Tip</div>
                <p class="dashboard">To view a <i>specific inactive</i> page, use the following link format:<br><a target="_blank" href="https://postredaudio.com?h=page-hash">https://postredaudio.com<span style="color: red">?h=<i>*page-hash*</i></span></a></br>
                <i>page-hash</i> is the value assigned to the individual page.</p>
                '),
            NovaTextCard::make()
                ->height('fixed')
                ->width('1/3')
                ->content('
                <div class="tip">Tip</div>
                <p class="dashboard">Drag elements to change their order.<br>Draggable elements: <a href="' . url('/postred-panel/resources/projects') . '"><span style="color: red">Projects</span></a>,
                <a href="' . url('/postred-panel/resources/services') . '"><span style="color: red">Services</span></a>,
                <a href="' . url('/postred-panel/resources/industries') . '"><span style="color: red">Industries</span></a>,
                <a href="' . url('/postred-panel/resources/departments') . '"><span style="color: red">Team Members</span></a> inside Departments,
                <span style="color: red">Cast</span> inside Projects,
                <span style="color: red">Content Blocks</span> inside Services, Industries and Pages.</p>
                '),
        ];
    }

    /**
     * Get the URI key for the dashboard.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'home';
    }
}
