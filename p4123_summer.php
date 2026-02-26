<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TestResults;

$p4123Fruct = TestResults::where('PatientId', 4123)->where('TestId', 308)->whereBetween('created_at', ['2025-05-01', '2025-07-31'])->get();
echo "Fructosamine for 4123 (Summer 2025):\n";
foreach ($p4123Fruct as $r) {
    echo "ID: {$r->id}, Value: {$r->ResultValue}, Date: {$r->created_at}\n";
}
