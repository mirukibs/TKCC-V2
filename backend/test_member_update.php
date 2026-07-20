<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use App\Modules\Members\Application\DTOs\UpdateMemberDTO;
use App\Modules\Members\Application\Services\UpdateMemberService;
use App\Modules\Members\Infrastructure\Models\MemberModel;
use Illuminate\Contracts\Console\Kernel;

try {
    // Create a member
    $member = MemberModel::create([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'gender' => 'male',
        'marital_status' => 'single',
        'employment_status' => 'employed',
    ]);

    echo 'Created member ID: '.$member->id."\n";

    $service = app(UpdateMemberService::class);

    $dto = new UpdateMemberDTO(
        id: $member->id,
        firstName: 'Jane',
        lastName: 'Doe',
        middleName: null,
        dob: null,
        gender: 'female',
        maritalStatus: 'married',
        phone: null,
        position: null,
        employmentStatus: 'employed',
        employmentNotes: null,
        householdId: null
    );

    $updated = $service->execute($dto);
    echo 'Updated member ID: '.$updated->getId()."\n";
    echo 'New name: '.$updated->getName()->getFirstName()."\n";

} catch (Exception $e) {
    echo 'Error: '.$e->getMessage()."\n";
    echo $e->getTraceAsString()."\n";
}
