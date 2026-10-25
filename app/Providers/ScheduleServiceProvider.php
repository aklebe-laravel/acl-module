<?php
declare(strict_types = 1);

namespace Modules\Acl\app\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Modules\SystemBase\app\Providers\Base\ScheduleBaseServiceProvider;

class ScheduleServiceProvider extends ScheduleBaseServiceProvider
{
    /**
     * Bootstrap module scheduled tasks natively inside a fully dynamic single closure.
     *
     * @param  Schedule  $schedule
     *
     * @return void
     */
    protected function bootEnabledSchedule(Schedule $schedule): void
    {
        /**
         * Sync/import ldap users and groups
         */
        $schedule->command('ldap:sync-all')
            ->cron(config('ldap.import_cron'))
            ->when(function () {
                return config('ldap.enabled', false);
            })->after(function () {
                logger("ldap import cron finished.");
            })
        ->name('ldap-importer')
            ->withoutOverlapping(30);
    }
}
