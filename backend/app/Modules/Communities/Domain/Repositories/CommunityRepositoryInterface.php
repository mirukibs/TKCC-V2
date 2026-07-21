<?php

namespace App\Modules\Communities\Domain\Repositories;

use App\Modules\Communities\Domain\Entities\Community;

interface CommunityRepositoryInterface
{
    public function findById(int $id): ?Community;
    
    /**
     * @return Community[]
     */
    public function findAll(): array;
    
    public function save(Community $community): Community;
    
    public function delete(int $id): bool;
}
