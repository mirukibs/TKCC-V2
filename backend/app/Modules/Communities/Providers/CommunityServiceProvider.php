<?php

namespace App\Modules\Communities\Providers;

use App\Modules\Communities\Domain\Repositories\CommunityRepositoryInterface;
use App\Modules\Communities\Infrastructure\Repositories\EloquentCommunityRepository;
use Illuminate\Support\ServiceProvider;

class CommunityServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            CommunityRepositoryInterface::class,
            EloquentCommunityRepository::class
        );
    }

    public function boot()
    {
        // Add routes
        if (file_exists(__DIR__.'/../Presentation/Routes/api.php')) {
            $this->loadRoutesFrom(__DIR__.'/../Presentation/Routes/api.php');
        }

        // Add migrations
        if (is_dir(__DIR__.'/../Infrastructure/Database/Migrations')) {
            $this->loadMigrationsFrom(__DIR__.'/../Infrastructure/Database/Migrations');
        }
    }
}
