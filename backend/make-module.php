<?php

$moduleName = $argv[1] ?? null;

if (! $moduleName) {
    echo "Usage: php make-module.php <ModuleName>\n";
    exit(1);
}

$basePath = __DIR__."/app/Modules/{$moduleName}";

$directories = [
    'Domain/Entities',
    'Domain/ValueObjects',
    'Domain/Repositories',
    'Domain/Enums',
    'Domain/Policies',
    'Domain/Exceptions',
    'Domain/Factories',
    'Application/Services',
    'Application/DTOs',
    'Presentation/Controllers',
    'Presentation/Requests',
    'Presentation/Resources',
    'Presentation/Routes',
    'Infrastructure/Models',
    'Infrastructure/Repositories',
    'Infrastructure/Database/Migrations',
    'Infrastructure/Database/Factories',
];

foreach ($directories as $dir) {
    $path = "{$basePath}/{$dir}";
    if (! is_dir($path)) {
        mkdir($path, 0755, true);
        echo "Created directory: {$path}\n";
    }
}

// Create a basic route file for the module
$routePath = "{$basePath}/Presentation/Routes/api.php";
if (! file_exists($routePath)) {
    file_put_contents($routePath, "<?php\n\nuse Illuminate\\Support\\Facades\\Route;\n\nRoute::prefix('".strtolower($moduleName)."')->group(function () {\n    // Define routes here\n});\n");
    echo "Created file: {$routePath}\n";
}

echo "\nModule {$moduleName} generated successfully.\n";
