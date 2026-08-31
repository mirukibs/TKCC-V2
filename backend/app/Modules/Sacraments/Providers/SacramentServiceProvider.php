<?php

namespace App\Modules\Sacraments\Providers;

use App\Modules\Sacraments\Domain\Repositories\SacramentRepositoryInterface;
use App\Modules\Sacraments\Infrastructure\Repositories\SacramentRepository;
use Illuminate\Support\ServiceProvider;

class SacramentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SacramentRepositoryInterface::class, SacramentRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Infrastructure/Database/Migrations');
        $this->loadRoutesFrom(__DIR__.'/../Presentation/Routes/api.php');
    }
}
