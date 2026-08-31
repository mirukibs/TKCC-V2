<?php

namespace App\Modules\Zones\Providers;

use App\Modules\Zones\Domain\Repositories\ZoneRepositoryInterface;
use App\Modules\Zones\Infrastructure\Repositories\EloquentZoneRepository;
use Illuminate\Support\ServiceProvider;

class ZoneServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            ZoneRepositoryInterface::class,
            EloquentZoneRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}
