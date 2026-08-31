<?php

use App\Providers\AppServiceProvider;
use App\Providers\ModuleServiceProvider;

return [
    AppServiceProvider::class,
    ModuleServiceProvider::class,
    App\Modules\Members\Providers\MemberServiceProvider::class,
    App\Modules\Households\Providers\HouseholdServiceProvider::class,
    App\Modules\Communities\Providers\CommunityServiceProvider::class,
    App\Modules\Zones\Providers\ZoneServiceProvider::class,
];
