<?php

namespace Tests\Unit\Modules\Members\Domain\Policies;

use App\Modules\Members\Domain\Exceptions\InvalidMemberDataException;
use App\Modules\Members\Domain\Factories\MemberFactory;
use App\Modules\Members\Domain\Policies\MemberRegistrationPolicy;
use PHPUnit\Framework\TestCase;

class MemberRegistrationPolicyTest extends TestCase
{
    public function test_allows_valid_member()
    {
        $member = MemberFactory::create(
            firstName: 'John',
            lastName: 'Doe'
        );

        $this->expectNotToPerformAssertions();
        $policy = new MemberRegistrationPolicy;
        $policy->enforce($member);
    }

    public function test_throws_exception_for_empty_first_name()
    {
        $this->expectException(\InvalidArgumentException::class);

        $member = MemberFactory::create(
            firstName: '',
            lastName: 'Doe'
        );

        $policy = new MemberRegistrationPolicy;
        $policy->enforce($member);
    }

    public function test_throws_exception_for_empty_last_name()
    {
        $this->expectException(\InvalidArgumentException::class);

        $member = MemberFactory::create(
            firstName: 'John',
            lastName: ''
        );

        $policy = new MemberRegistrationPolicy;
        $policy->enforce($member);
    }

    public function test_can_have_invalid_domain_rule_maybe()
    {
        // Wait, MemberRegistrationPolicy only throws InvalidMemberDataException if there are more complex rules.
        // Let's ensure InvalidMemberDataException isn't thrown for valid.
        $member = MemberFactory::create(
            firstName: 'A',
            lastName: 'B'
        );

        $policy = new MemberRegistrationPolicy;
        try {
            $policy->enforce($member);
            $this->assertTrue(true);
        } catch (InvalidMemberDataException $e) {
            $this->fail('Valid member should not throw InvalidMemberDataException.');
        }
    }
}
