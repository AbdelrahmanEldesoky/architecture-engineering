<?php

namespace App\Providers;

use App\Models\CMS\News;
use App\Models\Employee\EmployeeReport;
use App\Models\Employee\EmployeeRequest;
use App\Models\GeneralRequests\GeneralRequestAdvance;
use App\Models\GeneralRequests\GeneralRequestCustody;
use App\Models\GeneralRequests\GeneralRequestMaintenanceCar;
use App\Models\GeneralRequests\GeneralRequestMaintenanceCarDetail;
use App\Models\GeneralRequests\GeneralRequestVacation;
use App\Models\GeneralRequests\GeneralRequestWorkNeed;
use App\Observers\Employee\EmployeeReportObserver;
use App\Observers\Employee\EmployeeRequestObserver;
use App\Observers\GeneralRequestObserver;
use App\Observers\NewsObserver;
use App\Observers\UploadImage;
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
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        News::observe(NewsObserver::class);
        EmployeeReport::observe(EmployeeReportObserver::class);
//        EmployeeRequest::observe(EmployeeRequestObserver::class);

        //general request for user store
        GeneralRequestMaintenanceCar::observe(GeneralRequestObserver::class);
        GeneralRequestWorkNeed::observe(GeneralRequestObserver::class);
        GeneralRequestVacation::observe(GeneralRequestObserver::class);
        GeneralRequestCustody::observe(GeneralRequestObserver::class);
        GeneralRequestAdvance::observe(GeneralRequestObserver::class);
        EmployeeRequest::observe(GeneralRequestObserver::class);
        //upload image for builder
        GeneralRequestMaintenanceCarDetail::observe(UploadImage::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
