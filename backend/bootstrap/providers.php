<?php

use App\Modules\Communities\Providers\CommunityServiceProvider;
use App\Modules\Households\Providers\HouseholdServiceProvider;
use App\Modules\Members\Providers\MemberServiceProvider;
use App\Modules\Zones\Providers\ZoneServiceProvider;
use App\Providers\AppServiceProvider;
use App\Providers\ModuleServiceProvider;

return [
    AppServiceProvider::class,
    ModuleServiceProvider::class,
    MemberServiceProvider::class,
    HouseholdServiceProvider::class,
    CommunityServiceProvider::class,
    ZoneServiceProvider::class,
];
