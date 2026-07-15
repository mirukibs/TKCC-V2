<?php

namespace App\Modules\Members\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\Members\Domain\Repositories\MemberRepositoryInterface;
use App\Modules\Members\Infrastructure\Repositories\MemberRepository;

class MemberServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(MemberRepositoryInterface::class, MemberRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
