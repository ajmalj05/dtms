<?php
use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$patientId = 9012; // Thomas P Koshy
$controller = app(\App\Http\Controllers\API\AiChatController::class);
$reflection = new \ReflectionClass($controller);

// 1. Get Context
$method = $reflection->getMethod('getPatientContext');
$method->setAccessible(true);
$context = $method->invokeArgs($controller, [$patientId, 'full']);

// 2. Generate Prompt
$promptMethod = $reflection->getMethod('buildSystemPrompt');
$promptMethod->setAccessible(true);
$prompt = $promptMethod->invokeArgs($controller, [$context]);

echo "--- NEW GENERATED PROMPT ---\n\n";
echo $prompt;
echo "\n\n";

// 3. Call AI
$aiMethod = $reflection->getMethod('callAI');
$aiMethod->setAccessible(true);
$userMessage = "prepare a detailed case summary of this patient";
$response = $aiMethod->invokeArgs($controller, [$prompt, $userMessage, [], 'gemini', null]);

echo "--- AI RESPONSE ---\n\n";
echo $response['response'] ?? $response['message'] ?? 'Error fetching response';
