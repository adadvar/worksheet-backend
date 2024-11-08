<?php

namespace App\Providers;

use App\Events\VisitWorksheet;
use App\Listeners\AddVisitedWorksheetLogToWorksheetRecentsTable;
use App\Listeners\AddVisitedWorksheetLogToWorksheetViewsTable;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

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
        VisitWorksheet::class => [
            AddVisitedWorksheetLogToWorksheetViewsTable::class,
            AddVisitedWorksheetLogToWorksheetRecentsTable::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
