<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$results = DB::table('test_results')
    ->where('PatientId', 4123)
    ->where('TestId', 308)
    ->orderBy('created_at', 'desc')
    ->get();

foreach ($results as $r) {
    echo "ID: " . $r->id . " | Val: " . $r->ResultValue . " | Date: " . $r->created_at . "\n";
}
