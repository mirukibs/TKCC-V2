<?php

namespace App\Modules\Households\Providers;

use App\Modules\Households\Domain\Repositories\HouseholdRepositoryInterface;
use App\Modules\Households\Infrastructure\Repositories\EloquentHouseholdRepository;
use Illuminate\Support\ServiceProvider;

class HouseholdServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            HouseholdRepositoryInterface::class,
            EloquentHouseholdRepository::class
        );
    }

    public function boot(): void
    {
        // Boot module services
    }
}
