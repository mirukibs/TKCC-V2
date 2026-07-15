<?php

namespace App\Providers;

use File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $modulesPath = app_path('Modules');

        if (! File::exists($modulesPath)) {
            return;
        }

        $modules = File::directories($modulesPath);

        foreach ($modules as $module) {
            // Load Migrations
            $migrationPath = $module.'/Infrastructure/Database/Migrations';
            if (File::exists($migrationPath)) {
                $this->loadMigrationsFrom($migrationPath);
            }

            // Load API Routes
            $apiRoutePath = $module.'/Presentation/Routes/api.php';
            if (File::exists($apiRoutePath)) {
                Route::middleware('api')
                    ->prefix('api')
                    ->group($apiRoutePath);
            }

            // Register Providers
            $providersPath = $module.'/Providers';
            if (File::exists($providersPath)) {
                $files = File::files($providersPath);
                foreach ($files as $file) {
                    $className = 'App\\Modules\\'.basename($module).'\\Providers\\'.$file->getFilenameWithoutExtension();
                    if (class_exists($className)) {
                        $this->app->register($className);
                    }
                }
            }
        }
    }
}
