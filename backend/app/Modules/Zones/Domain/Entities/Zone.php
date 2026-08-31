<?php

namespace App\Modules\Zones\Domain\Entities;

use App\Modules\SharedKernel\Domain\ValueObjects\Name;

class Zone
{
    private ?int $id;
    private Name $name;

    public function __construct(?int $id, Name $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name->getValue();
    }
}
