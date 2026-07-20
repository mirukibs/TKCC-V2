<?php

namespace Tests\Unit\Modules\Members\Infrastructure\Repositories;

use App\Modules\Members\Domain\Factories\MemberFactory;
use App\Modules\Members\Infrastructure\Repositories\MemberRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private MemberRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new MemberRepository;
    }

    public function test_can_save_and_find_by_id()
    {
        $member = MemberFactory::create(
            firstName: 'John',
            lastName: 'Doe',
            phone: '0712345678'
        );

        $savedMember = $this->repository->save($member);
        $this->assertNotNull($savedMember->getId());

        $foundMember = $this->repository->findById($savedMember->getId());
        $this->assertNotNull($foundMember);
        $this->assertEquals('John', $foundMember->getName()->getFirstName());
        $this->assertEquals('+255712345678', $foundMember->getPhone()->getValue());
    }

    public function test_can_find_all_and_filter()
    {
        $member1 = MemberFactory::create(firstName: 'John', lastName: 'Doe');
        $member2 = MemberFactory::create(firstName: 'Jane', lastName: 'Smith');

        $this->repository->save($member1);
        $this->repository->save($member2);

        $results = $this->repository->findAll(['search' => 'Jane']);
        $this->assertCount(1, $results);
        $this->assertEquals('Jane', $results[0]->getName()->getFirstName());

        $allResults = $this->repository->findAll([]);
        $this->assertCount(2, $allResults);
    }

    public function test_can_delete()
    {
        $member = MemberFactory::create(firstName: 'John', lastName: 'Doe');
        $savedMember = $this->repository->save($member);

        $this->assertNotNull($this->repository->findById($savedMember->getId()));

        $this->repository->delete($savedMember->getId());

        $this->assertNull($this->repository->findById($savedMember->getId()));
    }
}
