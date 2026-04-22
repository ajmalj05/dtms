<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\PatientRegistration;
use App\Models\PatientDiagnosis;
use App\Models\PatientComplication;
use App\Models\PatientVitals;
use App\Models\PatientPrescriptions;
use App\Models\PatientMedicalHistory;
use App\Models\PatientVaccination;
use App\Models\PatientVisits;
use App\Models\PatientAlerts;
use App\Models\PatientBpStatus;
use App\Models\TestResults;

class AiChatController extends Controller
{
    /**
     * Maximum requests per minute per user
     */
    private const RATE_LIMIT = 20;

    // Intents
    private const INTENT_FULL = 'full';
    private const INTENT_VITALS = 'vitals';
    private const INTENT_MEDS = 'meds';
    private const INTENT_LABS = 'labs';

    /**
     * Handle AI chat request with patient context
     */
    public function chat(Request $request)
    {
        ini_set('memory_limit', '512M');
        try {
            // 1. Verify user is authenticated
            if (!Auth::check()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized access'
                ], 401);
            }

            $userId = Auth::id();

            // 2. Rate limiting
            $rateLimitKey = 'ai-chat:' . $userId;
            if (RateLimiter::tooManyAttempts($rateLimitKey, self::RATE_LIMIT)) {
                $seconds = RateLimiter::availableIn($rateLimitKey);
                return response()->json([
                    'status' => 'error',
                    'message' => "Too many requests. Please try again in {$seconds} seconds."
                ], 429);
            }
            RateLimiter::hit($rateLimitKey, 60);

            // 3. Validate input
            $validator = Validator::make($request->all(), [
                'patient_id' => 'required|integer|exists:patient_registration,id',
                'message' => 'required|string|max:2000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid input',
                    'errors' => $validator->errors()
                ], 422);
            }

            $patientId = (int) $request->patient_id;
            $userMessage = $this->sanitizeInput($request->message);

            // 4. Log the request
            Log::channel('daily')->info('AI Chat Request', [
                'user_id' => $userId,
                'patient_id' => $patientId, // Logged internally but not sent to AI
                'message_length' => strlen($userMessage)
            ]);

            // 5. Detect Intent & Gather Context
            $intent = $this->detectIntent($userMessage);
            $patientContext = $this->getPatientContext($patientId, $intent);

            if (!$patientContext) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Patient data not found'
                ], 404);
            }

            // 6. Build the AI prompt with ANONYMIZED patient context
            $systemPrompt = $this->buildSystemPrompt($patientContext);

            // 7. Get or Create Cache (Performance Optimization)
            $cachedContentName = $this->getOrCreateGeminiCache($patientId, $systemPrompt);

            // 8. Stream AI Response using SSE
            return response()->stream(function () use ($systemPrompt, $userMessage, $cachedContentName) {
                if (ob_get_level() > 0) {
                    ob_end_clean();
                }
                $this->streamAI($systemPrompt, $userMessage, $cachedContentName);
            }, 200, [
                'Cache-Control' => 'no-cache',
                'Content-Type' => 'text/event-stream',
                'X-Accel-Buffering' => 'no',
            ]);

        } catch (\Exception $e) {
            Log::error('AI Chat Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage() . ' on line ' . $e->getLine()
             ], 500);
        }
    }

    /**
     * Stream chunked response to frontend via Server-Sent Events.
     */
    private function streamAI(string $systemPrompt, string $userMessage, ?string $cachedContentName = null)
    {
        $provider = config('services.ai.provider', 'gemini');
        $model = 'gemini-2.5-flash'; // High-speed model for chat
        $apiKey = ($provider === 'gemini') ? config('services.gemini.api_key') : (config('services.ai.api_key') ?? config('services.gemini.api_key'));
        $formattedUserMessage = $this->buildChatUserPrompt($userMessage);

        if (!$apiKey) {
            echo "data: " . json_encode(['error' => 'API key missing']) . "\n\n";
            return;
        }

        $geminiBaseUrl = config('services.gemini.base_url', 'https://generativelanguage.googleapis.com/v1beta/models/');
        $url = $geminiBaseUrl . "{$model}:streamGenerateContent?alt=sse&key=" . $apiKey;

        $generationConfig = [
            'temperature' => 0.1,
            'topK' => 40,
            'topP' => 0.95,
            'maxOutputTokens' => 8192,
        ];

        $payload = [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        ['text' => ($cachedContentName ? $formattedUserMessage : ($systemPrompt . "\n\n" . $formattedUserMessage))]
                    ]
                ]
            ],
            'generationConfig' => $generationConfig,
        ];

        if ($cachedContentName) {
            $payload['cachedContent'] = $cachedContentName;
        } else {
            $payload['systemInstruction'] = [
                'parts' => [['text' => $systemPrompt]]
            ];
            $payload['safetySettings'] = [
                ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                ['category' => 'HARM_CATEGORY_HATE_SPEECH', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE']
            ];
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, false); 
        curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($curl, $data) {
            $lines = explode("\n", $data);
            foreach ($lines as $line) {
                if (str_starts_with($line, 'data: ')) {
                    $jsonStr = trim(substr($line, 6));
                    if ($jsonStr && $jsonStr !== '[DONE]') {
                        $decoded = json_decode($jsonStr, true);
                        if (isset($decoded['candidates'][0]['content']['parts'][0]['text'])) {
                            $text = $decoded['candidates'][0]['content']['parts'][0]['text'];
                            echo "data: " . json_encode(['text' => $text]) . "\n\n";
                            if (ob_get_level() > 0) { ob_flush(); }
                            flush();
                        }
                    }
                }
            }
            return strlen($data);
        });

        curl_exec($ch);
        if(curl_errno($ch)) {
            \Log::error("Gemini Streaming Error", ['curl_error' => curl_error($ch)]);
            echo "data: " . json_encode(['error' => 'Connection to AI server interrupted. Please refresh the page and try again.']) . "\n\n";
        }
        curl_close($ch);
        
        echo "data: [DONE]\n\n";
        if (ob_get_level() > 0) { ob_flush(); }
        flush();
    }

    /**
     * Generate patient summary and flags
     */
    public function getSummary(Request $request)
    {
        ini_set('memory_limit', '512M');
        try {
            if (!Auth::check()) {
                return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
            }

            $patientId = $request->patient_id;
            $forceRefresh = $request->boolean('force_refresh', false);

            if (!$patientId) {
                return response()->json(['status' => 'error', 'message' => 'Patient ID required'], 400);
            }

            $cacheKey = "ai_patient_analysis_" . $patientId;

            // Check cache unless refresh is forced
            if (!$forceRefresh && Cache::has($cacheKey)) {
                $cachedData = Cache::get($cacheKey);
                // Don't serve cached errors
                if (!isset($cachedData['is_error']) || !$cachedData['is_error']) {
                    return response()->json([
                        'status' => 'success',
                        'data' => $cachedData,
                        'cached' => true
                    ]);
                }
            }

            // GATHER CONTEXT
            $context = $this->getPatientContext($patientId);
            if (!$context) {
                return response()->json(['status' => 'error', 'message' => 'Patient not found'], 404);
            }
            $deterministicFlags = $this->buildDeterministicFlags($context);

            // Build Summary Prompt
            $systemPrompt = $this->buildSystemPrompt($context);

            $analysisPrompt  = "You are generating a formal AI-generated clinical summary report for a Specialized Diabetes Hospital.\n\n";

            $analysisPrompt .= "=== ABSOLUTE DATA RULE ===\n";
            $analysisPrompt .= "Use ONLY explicitly provided data. NEVER hallucinate, infer, or assume missing values. If a data point is absent, omit that section entirely.\n\n";

            $analysisPrompt .= "=== WRITING STYLE (MANDATORY) ===\n";
            $analysisPrompt .= "Write in a formal, third-person clinical AI voice. Use precise language as if the AI system itself is reporting.\n";
            $analysisPrompt .= "- Always reference trend data as: 'from X [unit] to Y [unit] over the past N visits/years'.\n";
            $analysisPrompt .= "- For worsening findings: end with a separate sentence starting with 'These findings suggest...' or 'This pattern suggests...' followed by 'The system flags this pattern as requiring clinical attention and closer monitoring.'\n";
            $analysisPrompt .= "- For improving findings: end with 'This improvement may reflect...' or 'Continued monitoring is recommended to maintain the current target.'\n";
            $analysisPrompt .= "- For static/stable findings: state clearly 'Current [parameters] are within recommended targets, indicating adequate management with current therapy.'\n";
            $analysisPrompt .= "- For medication/allergy notes: start with 'The system notes that the patient previously...'\n";
            $analysisPrompt .= "- For single elevated markers (no trend): state the value and its clinical significance, then 'Optimization of [risk area] management may be considered.'\n\n";

            $analysisPrompt .= "=== PATIENT SUMMARY ===\n";
            $analysisPrompt .= "Write a single flowing prose paragraph (2–4 sentences). Pattern: 'The patient is a [age]-year-old [gender] with a [N]-year history of [diagnosis 1], [condition 2] for [N] years, and [condition 3] for [N] years. He/She also has a history of [condition 4] diagnosed [N] years ago. His/Her current blood pressure is [BP] mmHg, and his/her BMI is [value] kg/m², which falls in the [category] range according to Asian Indian phenotype cut-offs, indicating increased cardiometabolic risk.' Include ONLY data that exists in the record.\n\n";

            $analysisPrompt .= "=== CLINICAL INSIGHTS ===\n";
            $analysisPrompt .= "Generate numbered clinical insight sections ONLY for areas where actual patient data exists. Use these exact title patterns based on the finding:\n";
            $analysisPrompt .= "  - Improving glycemic trend → 'Glycemic Control Trend'\n";
            $analysisPrompt .= "  - Worsening kidney markers → 'Renal Risk Alert'\n";
            $analysisPrompt .= "  - Improving FibroScan → 'Liver Fibrosis Improvement' | Worsening → 'Liver Fibrosis Progression'\n";
            $analysisPrompt .= "  - Elevated Lp(a), hsCRP, or CV risk factor → 'Cardiovascular Risk Marker'\n";
            $analysisPrompt .= "  - Lipid panel within/outside targets → 'Lipid Control Status'\n";
            $analysisPrompt .= "  - Drug intolerance or discontinuation → 'Medication Tolerance Alert'\n";
            $analysisPrompt .= "  - Blood pressure pattern → 'Blood Pressure Trend'\n";
            $analysisPrompt .= "  - Weight/BMI pattern → 'Weight and BMI Trend'\n\n";

            $analysisPrompt .= "For each insight, start the detail with a framing sentence: 'Analysis of [area] data shows...' or 'AI analysis of [area] parameters shows...'. For multi-parameter worsening insights (e.g. renal), list all worsening parameters in one paragraph with 'accompanied by...' and 'Additionally,...'. Then on a new paragraph, give the clinical interpretation.\n\n";

            $analysisPrompt .= "=== OVERALL AI INTERPRETATION (LAST INSIGHT — REQUIRED) ===\n";
            $analysisPrompt .= "Always include this as the final numbered insight. Title: 'Overall AI Interpretation'.\n";
            $analysisPrompt .= "Detail MUST follow this exact format:\n";
            $analysisPrompt .= "  Line 1: 'Based on analysis of longitudinal clinical data, the system summarizes the patient\\'s current status as:'\n";
            $analysisPrompt .= "  Then an HTML unordered list: <ul><li>[short status 1]</li><li>[short status 2]</li>...</ul>\n";
            $analysisPrompt .= "  Then a final plain text sentence: 'The system recommends [specific monitoring/action] during future visits.'\n\n";

            $analysisPrompt .= "=== FLAGS ===\n";
            $analysisPrompt .= "IMPORTANT: Official alert flags are computed by the backend deterministic engine. Do NOT generate additional free-form flags. Return an empty array for flags unless explicitly required by schema validation.\n\n";

            $analysisPrompt .= "=== CONCLUSION ===\n";
            $analysisPrompt .= "Start with '<b>Assessment:</b>' then overall clinical status summary. Then '<br><b>Plan:</b>' with specific next steps, monitoring frequency, and referrals if indicated.\n";

            $responseSchema = [
                'type' => 'OBJECT',
                'properties' => [
                    'patient_summary' => [
                        'type' => 'STRING',
                        'description' => 'A single flowing prose paragraph (2-4 sentences). Must follow this pattern: "The patient is a [age]-year-old [gender] with a [N]-year history of [diagnosis], [condition] for [N] years... His/Her current blood pressure is [BP] mmHg, and his/her BMI is [value] kg/m², which falls in the [category] range according to Asian Indian phenotype cut-offs, indicating increased cardiometabolic risk." Use ONLY data explicitly present in the record. No bullet points.'
                    ],
                    'insights' => [
                        'type' => 'ARRAY',
                        'items' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'title' => [
                                    'type' => 'STRING',
                                    'description' => 'Contextual title reflecting the clinical finding. Examples: "Glycemic Control Trend", "Renal Risk Alert", "Liver Fibrosis Improvement", "Cardiovascular Risk Marker", "Lipid Control Status", "Medication Tolerance Alert", "Blood Pressure Trend", "Overall AI Interpretation".'
                                ],
                                'detail' => [
                                    'type' => 'STRING',
                                    'description' => 'For standard insights: Start with "Analysis of [area] data shows..." or "AI analysis of [area] parameters shows...". Include specific numeric from→to values, trend direction, and a closing clinical significance or recommendation sentence. For multi-parameter insights list all parameters in one paragraph. For "Overall AI Interpretation" ONLY: start with "Based on analysis of longitudinal clinical data, the system summarizes the patient\'s current status as:" then an HTML <ul> with short <li> status statements, then a plain-text recommendation sentence starting with "The system recommends...".'
                                ]
                            ],
                            'required' => ['title', 'detail']
                        ],
                        'description' => 'Array of numbered clinical insight objects. Generate ONLY for areas with actual patient data. The last item MUST always be "Overall AI Interpretation" with the HTML bullet list format.'
                    ],
                    'flags' => [
                        'type' => 'ARRAY',
                        'items' => ['type' => 'STRING'],
                        'description' => "Reserved for compatibility. Keep empty where possible because deterministic backend computes official flags."
                    ],
                    'conclusion' => [
                        'type' => 'STRING',
                        'description' => "Start with '<b>Assessment:</b>' [overall clinical status in 1-2 sentences]. Then '<br><b>Plan:</b>' [specific next steps: monitoring intervals, referrals, medication adjustments]."
                    ]
                ],
                'required' => ['patient_summary', 'insights', 'flags', 'conclusion']
            ];

            // EXPERIMENTAL: Implementation of "Cached Input" via Gemini Context Caching
            $cachedContentName = $this->getOrCreateGeminiCache($patientId, $systemPrompt);

            // Call API with JSON enforcement - Using Gemini Structured Outputs
            $response = $this->callAI($systemPrompt, $analysisPrompt, [
                'responseMimeType' => 'application/json',
                'responseSchema' => $responseSchema
            ], 'gemini', $cachedContentName);
            
            if ($response['status'] === 'error') {
                return response()->json($response, 500);
            }
            
            $jsonObj = json_decode($response['response'], true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::warning('AI Analysis JSON Decode Failed (Structured Output Fallback)', [
                    'error' => json_last_error_msg(),
                    'raw_response' => $response['response']
                ]);
                
                $finalData = [
                    'patient_summary' => 'N/A',
                    'insights' => [],
                    'flags' => $deterministicFlags['flags'],
                    'flags_structured' => $deterministicFlags['flags_structured'],
                    'conclusion' => 'Analysis failed. Please try again.',
                    'is_error' => true
                ];
            } else {
                $finalData = [
                    'patient_summary' => trim($jsonObj['patient_summary'] ?? 'N/A'),
                    'insights' => $jsonObj['insights'] ?? [],
                    'flags' => $deterministicFlags['flags'],
                    'flags_structured' => $deterministicFlags['flags_structured'],
                    'conclusion' => trim($jsonObj['conclusion'] ?? ''),
                    'is_error' => false
                ];
            }

            // CACHE THE RESULT FOR 24 HOURS (Only if it's not a formatting error)
            if (!$finalData['is_error']) {
                // Store usage in cache too if needed, or just data
                $finalData['usage'] = $response['usage'] ?? [];
                Cache::put($cacheKey, $finalData, 86400);
            }

            return response()->json([
                'status' => 'success',
                'data' => $finalData,
                'cached' => false,
                'context_cached' => !!$cachedContentName
            ]);

        } catch (\Exception $e) {
            Log::error('AI Summary Error', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'error', 'message' => 'Analysis failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Sanitize user input
     */
    private function sanitizeInput(string $input): string
    {
        $input = strip_tags($input);
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        return substr($input, 0, 2000);
    }

    /**
     * Detect user intent for optimization
     */
    private function detectIntent(string $message): string
    {
        $msg = strtolower($message);
        
        // Vitals / BP
        if (preg_match('/(vital|bp|pressure|pulse|heart|weight|bmi|height|temp|fever)/', $msg)) {
            return self::INTENT_VITALS;
        }
        
        // Medications
        if (preg_match('/(med|drug|pill|tablet|prescri|dose|insulin|injection)/', $msg)) {
            return self::INTENT_MEDS;
        }

        
        // Labs
        if (preg_match('/(lab|result|test|report|hba1c|sugar|blood|urine|creatinine|egfr|lipid|cholesterol)/', $msg)) {
            return self::INTENT_LABS;
        }

        return self::INTENT_FULL;
    }

    /**
     * Gather patient context with filtering
     */
    private function getPatientContext(int $patientId, string $intent = self::INTENT_FULL): ?array
    {
        $cacheKey = "dtms_ai_context_{$patientId}_{$intent}";
        
        return Cache::remember($cacheKey, 300, function () use ($patientId, $intent) {
            // Basic patient information
            $patient = PatientRegistration::select(
                'patient_registration.*',
                'patient_type_master.patient_type_name',
                'salutation_master.salutation_name',
                'category_master.category_name'
            )
                ->leftJoin('patient_type_master', 'patient_type_master.id', '=', 'patient_registration.patient_type')
                ->leftJoin('salutation_master', 'salutation_master.id', '=', 'patient_registration.salutation_id')
                ->leftJoin('patient_category', 'patient_category.patient_id', '=', 'patient_registration.id')
                ->leftJoin('category_master', 'category_master.id', '=', 'patient_category.category') 
                ->where('patient_registration.id', $patientId)
                ->first();

            if (!$patient) {
                return null;
            }

            // Calculate age
            $age = null;
            if ($patient->dob) {
                $age = \Carbon\Carbon::parse($patient->dob)->age;
            }

            // Get gender
            $gender = 'Unknown';
            if ($patient->gender) {
                if (str_contains(strtolower($patient->gender), 'm')) $gender = 'Male';
                elseif (str_contains(strtolower($patient->gender), 'f')) $gender = 'Female';
                else $gender = 'Other';
            }

            // Diagnoses (Fixed table names)
            $diagnoses = PatientDiagnosis::select(
                'patient_diagnosis.*',
                'diagnosis_master.diagnosis_name',
                'subdiagnosis_master.subdiagnosis_name'
            )
                ->leftJoin('diagnosis_master', 'diagnosis_master.id', '=', 'patient_diagnosis.diagnosis_id')
                ->leftJoin('subdiagnosis_master', 'subdiagnosis_master.id', '=', 'patient_diagnosis.sub_diagnosis_id')
                ->where('patient_diagnosis.patient_id', $patientId)
                ->get();

            // Complications 
            $complications = PatientComplication::select(
                'patient_complication.*',
                'complication_master.complication_name',
                'subcomplication_master.subcomplication_name'
            )
                ->leftJoin('complication_master', 'complication_master.id', '=', 'patient_complication.complication_id')
                ->leftJoin('subcomplication_master', 'subcomplication_master.id', '=', 'patient_complication.sub_complication_id')
                ->where('patient_complication.patient_id', $patientId)
                ->get();

            // Filter: Vitals
            $vitals = collect([]);
            if ($intent === self::INTENT_FULL || $intent === self::INTENT_VITALS) {
                $vitals = PatientVitals::select(
                    'patient_vitals.*',
                    'vitals_master.vital_name'
                )
                    ->leftJoin('vitals_master', 'vitals_master.id', '=', 'patient_vitals.vitals_id')
                    ->where('patient_vitals.patient_id', $patientId)
                    ->orderBy('patient_vitals.created_at', 'desc')
                    ->get()
                    ->unique('vitals_id');
            }

            // Filter: Prescriptions
            $prescriptions = collect([]);
            if ($intent === self::INTENT_FULL || $intent === self::INTENT_MEDS) {
                $prescriptions = PatientPrescriptions::select(
                    'patient_prescriptions.*',
                    'medicine_master.medicine_name',
                    'tablet_type_master.tablet_type_name'
                )
                    ->leftJoin('medicine_master', 'medicine_master.id', '=', 'patient_prescriptions.medicine_id')
                    ->leftJoin('tablet_type_master', 'tablet_type_master.id', '=', 'medicine_master.tablet_type_id')
                    ->where('patient_prescriptions.patient_id', $patientId)
                    ->orderBy('patient_prescriptions.created_at', 'desc')
                    ->take(50) 
                    ->get();
            }

            // Medical history (Always fetch, it's small and important context)
            $medicalHistory = PatientMedicalHistory::where('patient_id', $patientId)
                ->orderBy('id', 'desc')
                ->first();

            // Filter: Test results
            $testResults = collect([]);
            if ($intent === self::INTENT_FULL || $intent === self::INTENT_LABS) {
                $testResults = TestResults::select(
                    'test_results.*',
                    'test_master.TestName'
                )
                    ->leftJoin('test_master', 'test_master.TestId', '=', 'test_results.TestId')
                    ->where('test_results.PatientId', $patientId)
                    ->orderBy('test_results.created_at', 'desc')
                    ->take(100) // Limit to prevent token bloat
                    ->get();
            }

            // Filter: BP Status (Vitals)
            $bpStatus = collect([]);
            if ($intent === self::INTENT_FULL || $intent === self::INTENT_VITALS) {
                 $bpStatus = PatientBpStatus::select('patient_bpstatus.*')
                    ->join('patient_visits', 'patient_visits.id', '=', 'patient_bpstatus.visit_id')
                    ->where('patient_visits.patient_id', $patientId)
                    ->orderBy('patient_bpstatus.created_at', 'desc')
                    ->take(10)
                    ->get();
            }

            // Visits
            $visits = PatientVisits::select(
                'patient_visits.*',
                'visit_type_master.visit_type_name'
            )
                ->leftJoin('visit_type_master', 'visit_type_master.id', '=', 'patient_visits.visit_type_id')
                ->where('patient_visits.patient_id', $patientId)
                ->orderBy('patient_visits.visit_date', 'desc')
                ->take(50) // Limit to relevant context
                ->get();

            // Vaccinations
            $vaccinations = PatientVaccination::select(
                'patient_vaccination.*',
                'vaccination_master.vaccination_name'
            )
                ->leftJoin('vaccination_master', 'vaccination_master.id', '=', 'patient_vaccination.vaccination_id')
                ->where('patient_vaccination.patient_id', $patientId)
                ->get();

            // Alerts
            $alerts = PatientAlerts::select('alerts', 'psychiatric_medications', 'created_at')
                ->where('patient_id', $patientId)
                ->orderBy('created_at', 'desc')
                ->first();

            // eGFR
            $egfr = $this->calculateEGFR($patientId, $age, $gender);

            return [
                'patient' => $patient,
                'age' => $age,
                'gender' => $gender,
                'diagnoses' => $diagnoses,
                'complications' => $complications,
                'vitals' => $vitals,
                'prescriptions' => $prescriptions,
                'medicalHistory' => $medicalHistory,
                'testResults' => $testResults,
                'bpStatus' => $bpStatus,
                'visits' => $visits,
                'vaccinations' => $vaccinations,
                'alerts' => $alerts,
                'egfr' => $egfr,
            ];
        });
    }

    private function calculateEGFR(int $patientId, ?int $age, string $gender): ?float
    {
        $scr = DB::table('test_results')
            ->where('PatientId', $patientId)
            ->where('TestId', 21)
            ->orderBy('created_at', 'desc')
            ->value('ResultValue');

        if ($scr === null || $age === null || $gender === 'Unknown') {
            return null;
        }

        $scr = (float) $scr;
        if ($scr <= 0) return null;

        if (strtolower($gender) === 'female') {
            $val = ($scr <= 0.7) ? -0.329 : -1.209;
            // Female multiplier is 141 * 1.018, Kappa is 0.7
            $egfr = (141 * 1.018) * pow(($scr / 0.7), $val) * pow(0.993, $age);
        } else {
            $val = ($scr <= 0.9) ? -0.411 : -1.209;
            // Male multiplier is 141, Kappa is 0.9
            $egfr = 141 * pow(($scr / 0.9), $val) * pow(0.993, $age);
        }

        return round($egfr, 2);
    }

    /**
     * Build system prompt with ANONYMIZED patient context
     */
    private function buildSystemPrompt(array $context): string
    {
        $patient = $context['patient'];
        
        $prompt = "You are the specialized AI Assistant for a Comprehensive Diabetes Hospital. ";
        $prompt .= "You are assisting a Diabetologist in a clinical setting. ";
        $prompt .= "Be direct, professional, and focus on diabetic care parameters. ";
        $prompt .= "When discussing visits, prioritize ACTUAL clinical/physical visits over administrative contacts like phone calls. ";
        $prompt .= "Do NOT use phrases like 'Based on the provided data' or 'The patient'. ";
        $prompt .= "Instead, directly state the findings if relevant.\n";
        $prompt .= "CRITICAL INSTRUCTIONS:\n";
        $prompt .= "1. **ANSWER ONLY WHAT IS ASKED.** Do not dump clinical data unless the user specifically asks for a summary or status.\n";
        $prompt .= "2. **GREETINGS:** If the user says 'Hi' or 'Hello', simply reply courteously (e.g., 'Hello. Ready to review this patient.') without listing any medical data.\n";
        $prompt .= "3. **DEFAULT FORMAT:** Prefer short bullet points for almost every answer. Use 2 to 5 bullets unless the question truly needs one short sentence.\n";
        $prompt .= "4. **CONCISENESS:** Keep responses very short. Each bullet should usually be one line only.\n";
        $prompt .= "5. **READABILITY:** For labs, meds, vitals, and history, put each item on its own bullet. Never return dense paragraphs or long blocks of plain text.\n";
        $prompt .= "6. **LAB RESPONSES:** For lab result questions, show each test as one bullet in the format '- Test: value (date)'. If there are many results, show only the most relevant or latest items first.\n";
        $prompt .= "7. **PRECISION:** Be precise. Do not summarize drug classes; list the specific medication names.\n";
        $prompt .= "8. **SAFETY:** If asked for treatment decisions, give brief clinical support information and suggest doctor review when appropriate.\n";
        $prompt .= "9. **NO HTML TABLES:** Use plain bullets only. No markdown tables.\n";
        
        // --- ANONYMIZED DEMOGRAPHICS ---
        $prompt .= "=== DATA (ANONYMIZED) ===\n";
        // REMOVED: Name, UHID, Mobile, DOB to protect identity
        $prompt .= "Age: " . ($context['age'] ?? 'N/A') . " years\n";
        $prompt .= "Gender: " . $context['gender'] . "\n";
        $prompt .= "Category: " . ($patient->category_name ?? 'N/A') . "\n";
        $prompt .= "Blood Group: " . ($patient->blood_group_id ?? 'N/A') . "\n\n";

        if ($context['alerts']) {
            $prompt .= "=== CRITICAL ALERTS ===\n";
            if ($context['alerts']->alerts) $prompt .= "- ALERT: " . $context['alerts']->alerts . "\n";
            if ($context['alerts']->psychiatric_medications) $prompt .= "- PSYCHIATRIC MEDS: " . $context['alerts']->psychiatric_medications . "\n";
            $prompt .= "\n";
        }

        // ... Keep identifying data OUT ...

        $prompt .= "=== DIAGNOSES ===\n";
        if ($context['diagnoses']->count() > 0) {
            foreach ($context['diagnoses'] as $diagnosis) {
                $prompt .= "- " . ($diagnosis->diagnosis_name ?? 'Unknown');
                if ($diagnosis->subdiagnosis_name) {
                    $prompt .= " (" . $diagnosis->subdiagnosis_name . ")";
                }
                if ($diagnosis->since) {
                    $prompt .= " - Since: " . $diagnosis->since;
                }
                $prompt .= "\n";
            }
        } else {
            $prompt .= "No diagnoses recorded\n";
        }
        $prompt .= "\n";

        $prompt .= "=== COMPLICATIONS ===\n";
        if ($context['complications']->count() > 0) {
            foreach ($context['complications'] as $complication) {
                $prompt .= "- " . ($complication->complication_name ?? 'Unknown');
                if ($complication->subcomplication_name) {
                    $prompt .= " (" . $complication->subcomplication_name . ")";
                }
                $prompt .= "\n";
            }
        } else {
            $prompt .= "No complications recorded\n";
        }
        $prompt .= "\n";

        $prompt .= "=== VITAL SIGNS (Latest) ===\n";
        if ($context['vitals']->count() > 0) {
            foreach ($context['vitals'] as $vital) {
                $prompt .= "- " . ($vital->vital_name ?? 'Unknown') . ": " . ($vital->vitals_value ?? 'N/A') . "\n";
            }
        } else {
            $prompt .= "No vital signs recorded\n";
        }
        if ($context['egfr']) {
            $prompt .= "- eGFR (Calculated): " . $context['egfr'] . " mL/min/1.73m²\n";
        }
        $prompt .= "\n";

        $prompt .= "=== LATEST PRESCRIPTION / CURRENT MEDICATIONS ===\n";
        if ($context['prescriptions']->count() > 0) {
            // Group by Date (Y-m-d) to isolate the latest prescription batch
            $groupedRx = $context['prescriptions']->groupBy(function($item) {
                return \Carbon\Carbon::parse($item->created_at)->format('d-m-Y');
            });
            
            // Get the MOST RECENT date group
            $latestDate = $groupedRx->keys()->first(); 
            $latestMeds = $groupedRx->first();

            $prompt .= "Date: " . $latestDate . "\n";
            foreach ($latestMeds as $rx) {
                $medName = $rx->medicine_name ?? $rx->medicine_other ?? 'Unknown';
                $prompt .= "- " . $medName;
                if ($rx->tablet_type_name) {
                    $prompt .= " (" . $rx->tablet_type_name . ")";
                }
                if ($rx->frequency) {
                    $prompt .= " - " . $rx->frequency;
                }
                if ($rx->dose) {
                    $prompt .= " - Dosage: " . $rx->dose;
                }
                if ($rx->duration) { 
                     $prompt .= " - Duration: " . $rx->duration;
                }
                $prompt .= "\n";
            }
        } else {
            $prompt .= "No active prescriptions found.\n";
        }
        $prompt .= "\n";

        $prompt .= "=== LAB TEST HISTORY (GROUPED TRENDS) ===\n";
        if ($context['testResults']->count() > 0) {
            $groupedResults = $context['testResults']->groupBy('TestName');
            foreach ($groupedResults as $testName => $results) {
                $latest = $results->first();
                $prompt .= "- {$testName}: Latest " . ($latest->ResultValue ?? 'N/A') . ($latest->unit ? " " . $latest->unit : "");
                $prompt .= " (Date: " . ($latest->created_at ? \Carbon\Carbon::parse($latest->created_at)->format('d-m-Y') : 'N/A') . ")";
                
                    // Provide up to the last 5 previous results for proper trend analysis
                    $previousResultsStr = [];
                    $count = 0;
                    // Start from index 1 because index 0 is the "latest" which we already appended above
                    for ($i = 1; $i < $results->count() && $count < 5; $i++) {
                        $p = $results->get($i);
                        if ($p && isset($p->ResultValue)) {
                            $pDate = $p->created_at ? \Carbon\Carbon::parse($p->created_at)->format('d-m-Y') : 'Unknown Date';
                            $previousResultsStr[] = $p->ResultValue . " (" . $pDate . ")";
                            $count++;
                        }
                    }
                    
                    if (!empty($previousResultsStr)) {
                        $prompt .= " [Previous History (Newest to Oldest): " . implode(" -> ", $previousResultsStr) . "]";
                    }
                    
                    // Historical range for long-term insight
                    $values = $results->pluck('ResultValue')->map(fn($v) => (float)$v)->filter();
                    if ($values->count() > 0) {
                        $prompt .= " | Hist. Range: " . $values->min() . " - " . $values->max();
                    }
                $prompt .= "\n";
            }
        } else {
            $prompt .= "No recent test results record\n";
        }
        $prompt .= "\n";

        $prompt .= "=== BLOOD PRESSURE HISTORY ===\n";
        if ($context['bpStatus']->count() > 0) {
            foreach ($context['bpStatus']->take(5) as $bp) {
                $prompt .= "- " . ($bp->bp_systolic ?? 'N/A') . "/" . ($bp->bp_diastolic ?? 'N/A') . " mmHg";
                if ($bp->pulse) {
                    $prompt .= ", Pulse: " . $bp->pulse . " bpm";
                }
                $prompt .= " (Date: " . ($bp->created_at ? \Carbon\Carbon::parse($bp->created_at)->format('d-m-Y') : 'N/A') . ")\n";
            }
        } else {
            $prompt .= "No BP records\n";
        }
        $prompt .= "\n";

        $prompt .= "=== MEDICAL HISTORY ===\n";
        if ($context['medicalHistory']) {
            $mh = $context['medicalHistory'];
            if ($mh->family_history) $prompt .= "Family History: " . $mh->family_history . "\n";
            if ($mh->past_history) $prompt .= "Past History: " . $mh->past_history . "\n";
            if ($mh->surgical_history) $prompt .= "Surgical History: " . $mh->surgical_history . "\n";
            if ($mh->allergy) $prompt .= "Allergies: " . $mh->allergy . "\n";
        } else {
            $prompt .= "No detailed medical history recorded\n";
        }
        $prompt .= "\n";

        $prompt .= "=== VACCINATIONS ===\n";
        if ($context['vaccinations']->count() > 0) {
            foreach ($context['vaccinations'] as $vaccine) {
                $prompt .= "- " . ($vaccine->vaccination_name ?? 'Unknown');
                if ($vaccine->vaccination_date) {
                    $prompt .= " (Date: " . $vaccine->vaccination_date . ")";
                }
                $prompt .= "\n";
            }
        } else {
            $prompt .= "No vaccinations recorded\n";
        }
        $prompt .= "\n";

        $prompt .= "=== COMPLETE VISITS HISTORY ===\n";
        if ($context['visits']->count() > 0) {
            foreach ($context['visits'] as $visit) { 
                $prompt .= "- Date: " . ($visit->visit_date ? \Carbon\Carbon::parse($visit->visit_date)->format('d-m-Y') : 'N/A');
                $prompt .= " (" . ($visit->visit_type_name ?? 'General') . ")";
                if ($visit->dtms_remarks) {
                    $prompt .= " - Note: " . $visit->dtms_remarks;
                }
                $prompt .= "\n";
            }
        } else {
            $prompt .= "No visits recorded\n";
        }
        $prompt .= "\n";

        $prompt .= "=== PATIENT ALERTS ===\n";
        if ($context['alerts']) {
            $prompt .= $context['alerts']->alert_message ?? "No alerts\n";
        } else {
            $prompt .= "No alerts\n";
        }
        $prompt .= "\n";

        $prompt .= "=== INSTRUCTIONS ===\n";
        $prompt .= "1. You are an internal system AI. Be direct, authoritative, and concise.\n";
        $prompt .= "2. NEVER say 'Based on the data', 'The patient', or 'according to records'.\n";
        $prompt .= "3. Start answers directly: 'Diagnosis is...', 'Medications indicate...', 'BP history shows...'.\n";
        $prompt .= "4. Do NOT mention the patient's name or personal details.\n";
        $prompt .= "5. If 'Diagnosis' is empty, you may gently note what conditions medications commonly treat (e.g., 'Metformin suggests diabetes'), but YOU MUST NOT definitively infer or state severe diseases (especially Cancer) unless explicitly listed in the DIAGNOSES section.\n";
        $prompt .= "6. ABSOLUTE RULE: You MUST ONLY use the explicit data provided in this prompt. YOU ARE STRICTLY FORBIDDEN FROM HALLUCINATING, GUESSING, INFERRING, OR ASSUMING ANY MEDICAL CONDITIONS, TREATMENTS, OR MISSING DATA POINTS.\n";
        $prompt .= "7. If a user asks for data that is missing from this prompt, reply EXACTLY with 'Data not available in records.' Do not offer explanations.\n";
        $prompt .= "8. END every response with exactly this format (1 blank line, italicized): \n\n*Verify with clinical judgment.*\n";
        
        return $prompt;
    }

    private function buildChatUserPrompt(string $userMessage): string
    {
        return "User Question: {$userMessage}\n\n" .
            "Reply rules:\n" .
            "- Keep it short.\n" .
            "- Prefer point-wise bullets.\n" .
            "- Use 2 to 5 bullets when possible.\n" .
            "- For multiple lab values, put one result per bullet with value and date.\n" .
            "- Avoid large paragraphs.\n";
    }

    private function callAI(string $systemPrompt, string $userMessage, array $configOverride = [], ?string $providerOverride = null, ?string $cachedContentName = null): array
    {
        $provider = $providerOverride ?? config('services.ai.provider', 'gemini');
        $apiKey = ($provider === 'gemini') ? config('services.gemini.api_key') : (config('services.ai.api_key') ?? config('services.gemini.api_key'));
        // Enforce Pro model for analysis tasks to guarantee accuracy and JSON structure adherence
        $model = 'gemini-2.5-flash';
        $baseUrl = config('services.ai.base_url');

        if (!$apiKey || !$model) {
            return [
                'status' => 'error',
                'message' => 'AI service is not configured properly. Check .env variables.'
            ];
        }

        try {
            // --- GEMINI HANDLER ---
            $geminiBaseUrl = config('services.gemini.base_url', 'https://generativelanguage.googleapis.com/v1beta/models/');
            $url = $geminiBaseUrl . "{$model}:generateContent?key=" . $apiKey;

            $generationConfig = array_merge([
                'temperature' => 0.1,
                'topK' => 40,
                'topP' => 0.95,
                'maxOutputTokens' => 8192,
            ], $configOverride);

            $payload = [
                'systemInstruction' => [
                    'parts' => [['text' => $systemPrompt]]
                ],
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [
                            ['text' => $userMessage]
                        ]
                    ]
                ],
                'generationConfig' => $generationConfig,
            ];

            if ($cachedContentName) {
                // Remove prompt when cache is present
                $payload['cachedContent'] = $cachedContentName;
                unset($payload['systemInstruction']);
            } else {
                // Include safety settings for normal requests
                $payload['safetySettings'] = [
                    ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                    ['category' => 'HARM_CATEGORY_HATE_SPEECH', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                    ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                    ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE']
                ];
            }

            $response = Http::timeout(60)->post($url, $payload);

            if ($response->successful()) {
                $data = $response->json();
                $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No response generated.';
                $usage = $data['usageMetadata'] ?? [];

                return [
                    'status' => 'success',
                    'response' => $text,
                    'usage' => $usage
                ];
            }

            // --- COMMON ERROR HANDLING ---
            $status = $response->status();
            $body = $response->body();
            
            Log::error('AI API Error (' . $provider . ')', [
                'status' => $status,
                'body' => $body
            ]);
            
            if ($status === 429) {
                return [
                    'status' => 'error',
                    'message' => 'AI Usage Limit Reached. Please wait a moment.'
                ];
            }
            
            return [
                'status' => 'error',
                'message' => 'Failed to get response from AI service (' . $status . ').'
            ];

        } catch (\Exception $e) {
            Log::error('AI Service Exception', ['error' => $e->getMessage()]);
            return [
                'status' => 'error',
                'message' => 'AI service is temporarily unavailable.'
            ];
        }
    }

    /**
     * Analyze the trend of a specific test based on historical data.
     */
    public function analyzeTrend(Request $request)
    {
        $request->validate([
            'test_name' => 'required|string',
            'history' => 'required|array', // Array of {date, value, unit}
        ]);

        $testName = $request->test_name;
        $history = $request->history;
        $patientContext = "Patient Age: " . ($request->age ?? 'Unknown') . ", Gender: " . ($request->gender ?? 'Unknown');

        // 1. Build the Prompt
        $prompt = "You are a clinical pathologist assisting a doctor. Analyze the following trend for the test: **$testName**.\n\n";
        $prompt .= "CONTEXT: $patientContext\n\n";
        $prompt .= "DATA HISTORY (Oldest to Newest):\n";
        
        foreach ($history as $record) {
             $prompt .= "- Date: {$record['date']}, Value: {$record['value']}\n";
        }

        $prompt .= "1. **Analyze Full History:** Don't just look at Start vs. End. Look at the *entire* path. Are there sudden spikes between dates? Is the change gradual or instant?\n";
        $prompt .= "2. **Detailed Velocity:** Calculate the overall change (Last - First) AND identify the steepest drop/rise between specific adjacent dates.\n";
        $prompt .= "3. **Predictive Modeling:** Based solely on the current velocity and trajectory, estimate the potential direction/range for the *next* test result.\n";
        $prompt .= "4. **Clinical Context:** Interpret the medical significance.\n";
        $prompt .= "5. **ABSOLUTE RULE:** STRICT COMPLIANCE REQUIRED. Do not hallucinate, assume, or infer any unprovided patient conditions, metadata, or data points outside of the immediate context.\n";
        
        $prompt .= "\n=== OUTPUT RULES ===\n";
        $prompt .= "You MUST provide exactly 4 distinct bullet points. **Keep each point to ONE concise sentence (Max 25 words).**\n";
        $prompt .= "1. <b>Nuanced Pattern:</b> (e.g., 'Shows a dramatic drop from 22 to 7, indicating rapid normalization.')\n";
        $prompt .= "2. <b>Velocity & Rate:</b> (e.g., 'Dropped 15 units (68%) over 12 months.')\n";
        $prompt .= "3. <b>Future Prediction:</b> (e.g., 'Projected to stabilize around 6.5 - 6.8 by next test.')\n";
        $prompt .= "4. <b>Clinical Explanation:</b> (e.g., 'Suggests effective therapeutic intervention and significantly reduced risk.')\n";
        $prompt .= "Format: Use HTML <ul><li><b> only. NO Markdown.\n";

        // 2. Call AI
        $result = $this->callAI(
            "You are a precise clinical AI. Output strictly valid HTML <ul> and <li> tags.", 
            $prompt
        );

        if ($result['status'] === 'success') {
            return response()->json([
                'status' => 'success',
                'analysis' => $result['response'],
                'usage' => $result['usage'] ?? []
            ]);
        } else {
             return response()->json([
                'status' => 'error',
                'message' => 'AI Analysis failed: ' . ($result['message'] ?? 'Unknown error')
            ], 500);
        }
    }

    /**
     * Gemini Context Caching Logic
     * Creates or retrieves a CachedContent resource for large patient histories.
     */

    private function getOrCreateGeminiCache(int $patientId, string $systemPrompt): ?string
    {
        // Hash the prompt content to ensure unique cache for unique intents/data
        $contentHash = md5($systemPrompt);
        $cacheKey = "gemini_context_cache_v2_{$patientId}_{$contentHash}";
        
        $cachedId = Cache::get($cacheKey);

        if ($cachedId) {
            return $cachedId;
        }

        // Lowered threshold significantly to attempt caching for ~10k+ tokens
        if (strlen($systemPrompt) < 40000) { 
            return null;
        }

        try {
            $apiKey = config('services.gemini.api_key');
            $model = 'gemini-2.5-flash';
            $url = "https://generativelanguage.googleapis.com/v1beta/cachedContents?key=" . $apiKey;

            $payload = [
                'model' => 'models/' . $model,
                'displayName' => "patient_history_{$patientId}",
                'systemInstruction' => [
                    'parts' => [['text' => $systemPrompt]]
                ],
                'ttl' => '36000s' // 1 hour cache
            ];

            $response = Http::timeout(30)->post($url, $payload);

            if ($response->successful()) {
                $name = $response->json()['name'] ?? null;
                if ($name) {
                    \Log::info("Gemini Context Cache Created: {$name} for Patient: {$patientId}");
                    Cache::put($cacheKey, $name, 3500); // Store name in Laravel for 58 mins
                    
                    // Track this cache for deletion when patient records update
                    $trackerKey = "gemini_caches_for_{$patientId}";
                    $activeCaches = Cache::get($trackerKey, []);
                    if (!in_array($name, $activeCaches)) {
                        $activeCaches[] = $name;
                        // Keep array size reasonable
                        if (count($activeCaches) > 10) array_shift($activeCaches);
                        Cache::put($trackerKey, $activeCaches, 86400); // 1 day
                    }
                    
                    return $name;
                }
            } else {
                \Log::warning('Gemini Cache Creation Rejected', [
                    'status' => $response->status(),
                    'body' => $response->json(),
                    'char_count' => strlen($systemPrompt)
                ]);
            }
        } catch (\Exception $e) {
            \Log::warning('Gemini Context Caching Exception', ['error' => $e->getMessage()]);
        }

        return null; // Fallback to normal prompt
    }

    /**
     * Validate current prescription medicines using AI.
     * Checks: dose limits per patient profile, drug-drug interactions,
     * and patient-specific contraindications.
     */
    public function validatePrescription(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
            }

            $validator = Validator::make($request->all(), [
                'patient_id' => 'required|integer|exists:patient_registration,id',
                'medicines'  => 'required|array|min:1',
                'medicines.*.name'        => 'required|string',
                'medicines.*.dose'        => 'nullable|string',
                'medicines.*.frequency'   => 'nullable|string',
                'medicines.*.tablet_type' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Invalid input',
                    'errors'  => $validator->errors()
                ], 422);
            }

            $patientId = (int) $request->patient_id;
            $medicines = $request->medicines;

            // Fetch full patient context (age, gender, diagnoses, complications, medical history, allergies)
            $context = $this->getPatientContext($patientId, self::INTENT_FULL);
            if (!$context) {
                return response()->json(['status' => 'error', 'message' => 'Patient not found'], 404);
            }

            // --- Build the validation prompt ---
            $patient  = $context['patient'];
            $age      = $context['age'] ?? 'Unknown';
            $gender   = $context['gender'] ?? 'Unknown';

            $systemPrompt  = "You are a clinical pharmacologist AI embedded in a Diabetes Hospital system. ";
            $systemPrompt .= "Your ONLY job is to validate a prescription list against patient-specific safety rules. ";
            $systemPrompt .= "Be concise, clinical, and accurate. Do NOT hallucinate or guess missing data. ";
            $systemPrompt .= "Use ONLY the data explicitly provided. ";
            $systemPrompt .= "ABSOLUTE RULE: Never infer conditions not listed. Never assume knowledge beyond the provided context.\n";

            $userPrompt  = "=== PATIENT PROFILE ===\n";
            $userPrompt .= "Age: {$age} years\n";
            $userPrompt .= "Gender: {$gender}\n";

            // Diagnoses
            $diagnosesList = [];
            if ($context['diagnoses']->count() > 0) {
                foreach ($context['diagnoses'] as $d) {
                    $label = $d->diagnosis_name ?? 'Unknown';
                    if ($d->subdiagnosis_name) $label .= " ({$d->subdiagnosis_name})";
                    $diagnosesList[] = $label;
                }
            }
            $userPrompt .= "Diagnoses: " . (empty($diagnosesList) ? 'None recorded' : implode(', ', $diagnosesList)) . "\n";

            // Complications
            $compList = [];
            if ($context['complications']->count() > 0) {
                foreach ($context['complications'] as $c) {
                    $label = $c->complication_name ?? 'Unknown';
                    if ($c->subcomplication_name) $label .= " ({$c->subcomplication_name})";
                    $compList[] = $label;
                }
            }
            $userPrompt .= "Complications: " . (empty($compList) ? 'None recorded' : implode(', ', $compList)) . "\n";

            // Medical History (Allergies etc.)
            if ($context['medicalHistory']) {
                $mh = $context['medicalHistory'];
                if ($mh->allergy) $userPrompt .= "Known Allergies: {$mh->allergy}\n";
                if ($mh->past_history) $userPrompt .= "Past Medical History: {$mh->past_history}\n";
            }

            // eGFR
            if ($context['egfr']) {
                $userPrompt .= "eGFR (Calculated): {$context['egfr']} mL/min/1.73m²\n";
            }

            // Psychiatric alerts
            if ($context['alerts'] && $context['alerts']->psychiatric_medications) {
                $userPrompt .= "Psychiatric Medications on Record: {$context['alerts']->psychiatric_medications}\n";
            }

            $userPrompt .= "\n=== CURRENT PRESCRIPTION BEING VALIDATED ===\n";
            foreach ($medicines as $idx => $med) {
                $num = $idx + 1;
                $name  = $med['name'] ?? 'Unknown';
                $dose  = $med['dose'] ?? 'Not specified';
                $freq  = $med['frequency'] ?? 'Not specified';
                $type  = $med['tablet_type'] ?? '';
                $userPrompt .= "{$num}. Medicine: {$name}";
                if ($type) $userPrompt .= " [{$type}]";
                $userPrompt .= " | Dose: {$dose} | Frequency: {$freq}\n";
            }

            $userPrompt .= "\n=== VALIDATION INSTRUCTIONS ===\n";
            $userPrompt .= "Analyze the prescription for the following issues:\n";
            $userPrompt .= "1. DOSE SAFETY: Check if the dose of any individual medicine or the COMBINED dose of medicines sharing the same active ingredient exceeds the safe maximum for this patient's age and gender. Flag even if the combined total is slightly over the limit.\n";
            $userPrompt .= "2. DRUG INTERACTIONS: Identify any dangerous or clinically significant drug-drug interactions between the listed medicines (e.g., medicines that should not be taken together, or medicines conflicting when given at the same frequency/timing).\n";
            $userPrompt .= "3. CONTRAINDICATIONS: Flag any medicine that is contraindicated given the patient's diagnoses, complications, organ function (eGFR), allergies, or medical history.\n";
            $userPrompt .= "4. If there are NO issues at all, set overall_safe to true and return an empty issues array.\n";
            $userPrompt .= "5. Do NOT flag theoretical or extremely rare interactions unless they are clinically significant.\n";

            $responseSchema = [
                'type' => 'OBJECT',
                'properties' => [
                    'overall_safe' => [
                        'type' => 'BOOLEAN',
                        'description' => 'true if no significant safety issues are found, false if any issues exist.'
                    ],
                    'issues' => [
                        'type' => 'ARRAY',
                        'description' => 'List of identified safety issues. Empty array if overall_safe is true.',
                        'items' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'severity' => [
                                    'type' => 'STRING',
                                    'description' => 'One of: CRITICAL, WARNING, or INFO',
                                    'enum' => ['CRITICAL', 'WARNING', 'INFO']
                                ],
                                'type' => [
                                    'type' => 'STRING',
                                    'description' => 'One of: DOSE_EXCEEDED, DRUG_INTERACTION, CONTRAINDICATION, or OTHER',
                                    'enum' => ['DOSE_EXCEEDED', 'DRUG_INTERACTION', 'CONTRAINDICATION', 'OTHER']
                                ],
                                'medicines_involved' => [
                                    'type' => 'ARRAY',
                                    'items' => ['type' => 'STRING'],
                                    'description' => 'Names of the medicine(s) involved in this issue.'
                                ],
                                'explanation' => [
                                    'type' => 'STRING',
                                    'description' => 'Clear, concise clinical explanation of the issue (1-2 sentences max).'
                                ]
                            ],
                            'required' => ['severity', 'type', 'medicines_involved', 'explanation']
                        ]
                    ],
                    'recommendations' => [
                        'type' => 'STRING',
                        'description' => 'A brief overall recommendation for the prescribing physician (1-3 sentences). If overall_safe is true, state that the prescription appears clinically appropriate.'
                    ]
                ],
                'required' => ['overall_safe', 'issues', 'recommendations']
            ];

            $result = $this->callAI($systemPrompt, $userPrompt, [
                'responseMimeType' => 'application/json',
                'responseSchema'   => $responseSchema,
                'temperature'      => 0.1,
                'maxOutputTokens'  => 4096,
            ]);

            if ($result['status'] === 'error') {
                return response()->json(['status' => 'error', 'message' => $result['message'] ?? 'AI validation failed'], 500);
            }

            $jsonObj = json_decode($result['response'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::warning('AI Prescription Validation JSON Decode Failed', [
                    'error' => json_last_error_msg(),
                    'raw'   => $result['response']
                ]);
                return response()->json(['status' => 'error', 'message' => 'Failed to parse AI response. Please try again.'], 500);
            }

            return response()->json([
                'status' => 'success',
                'data'   => [
                    'overall_safe'    => $jsonObj['overall_safe'] ?? true,
                    'issues'          => $jsonObj['issues'] ?? [],
                    'recommendations' => $jsonObj['recommendations'] ?? '',
                ],
                'usage' => $result['usage'] ?? []
            ]);

        } catch (\Exception $e) {
            Log::error('AI Prescription Validation Error', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'error', 'message' => 'Validation failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Clear all cached AI context for a patient to prevent hallucinations from stale data.
     * This is called by the ClearsPatientAiCache trait on relevant Eloquent models.
     */
    public static function clearPatientAiCache(int $patientId)
    {
        // 1. Clear Laravel summary cache & individual context elements
        Cache::forget("ai_patient_analysis_" . $patientId);
        Cache::forget("dtms_ai_context_{$patientId}_" . self::INTENT_FULL);
        Cache::forget("dtms_ai_context_{$patientId}_" . self::INTENT_VITALS);
        Cache::forget("dtms_ai_context_{$patientId}_" . self::INTENT_MEDS);
        Cache::forget("dtms_ai_context_{$patientId}_" . self::INTENT_LABS);

        // 2. Clear known Gemini cached contents from Google Servers
        $trackerKey = "gemini_caches_for_{$patientId}";
        $activeCaches = Cache::get($trackerKey, []);
        
        $provider = config('services.ai.provider', 'gemini');
        $apiKey = ($provider === 'gemini') ? config('services.gemini.api_key') : (config('services.ai.api_key') ?? config('services.gemini.api_key'));

        if ($apiKey && !empty($activeCaches)) {
            foreach ($activeCaches as $cachedContentName) {
                try {
                    Http::timeout(10)->delete("https://generativelanguage.googleapis.com/v1beta/{$cachedContentName}?key={$apiKey}");
                } catch (\Exception $e) {
                    \Log::warning("Failed to delete Gemini Context Cache: {$cachedContentName}", ['error' => $e->getMessage()]);
                }
            }
        }
        
        Cache::forget($trackerKey);
    }

    private function buildDeterministicFlags(array $context): array
    {
        $structured = [];
        $maxHistoryPoints = (int) config('ai_flags.max_history_points', 5);
        $rules = config('ai_flags.rules', []);
        $includeInfoFlags = (bool) config('ai_flags.include_info_flags', false);
        $includePsychiatricNote = (bool) config('ai_flags.include_psychiatric_note', false);

        foreach ($this->extractLabTrends($context['testResults'] ?? collect(), $maxHistoryPoints) as $trend) {
            $rule = $this->matchTrendRule($trend['metric_key'], $rules);
            if (!$rule) {
                continue;
            }

            $severity = $this->resolveSeverity($trend, $rule);
            if (!$severity) {
                continue;
            }
            if ($severity === 'info' && !$includeInfoFlags) {
                continue;
            }

            $structured[] = $this->formatStructuredFlag($trend, $severity);
        }

        if (
            $includePsychiatricNote &&
            ($context['alerts'] ?? null) &&
            !empty($context['alerts']->psychiatric_medications)
        ) {
            $structured[] = [
                'metric' => 'Psychiatric medication history',
                'direction' => 'status',
                'from_value' => null,
                'to_value' => null,
                'unit' => null,
                'latest_date' => $context['alerts']->created_at ? \Carbon\Carbon::parse($context['alerts']->created_at)->format('d-m-Y') : null,
                'period' => 'latest record',
                'severity' => 'info',
                'message' => 'Psychiatric medication history is recorded; consider medication interactions during treatment planning.'
            ];
        }

        usort($structured, function ($a, $b) {
            $rank = ['critical' => 4, 'high' => 3, 'moderate' => 2, 'info' => 1];
            return ($rank[$b['severity']] ?? 0) <=> ($rank[$a['severity']] ?? 0);
        });

        return [
            'flags' => array_values(array_map(fn($row) => $row['message'], $structured)),
            'flags_structured' => array_values($structured),
        ];
    }

    private function extractLabTrends($testResults, int $maxHistoryPoints = 5): array
    {
        if (!$testResults || $testResults->count() === 0) {
            return [];
        }

        $trends = [];
        $grouped = $testResults->groupBy('TestName');

        foreach ($grouped as $testName => $rows) {
            $metricName = trim((string) $testName);
            $metricKey = $this->normalizeMetricKey($metricName);
            $ordered = $rows->sortBy(function ($row) {
                return $row->created_at ? \Carbon\Carbon::parse($row->created_at)->timestamp : 0;
            })->values();

            $window = $ordered->slice(max(0, $ordered->count() - $maxHistoryPoints))->values();
            if ($window->count() < 2) {
                continue;
            }

            $start = $window->first();
            $latest = $window->last();
            $from = $this->toNumeric($start->ResultValue ?? null);
            $to = $this->toNumeric($latest->ResultValue ?? null);
            if ($from === null || $to === null) {
                continue;
            }

            $delta = $to - $from;
            $percentChange = $from == 0.0 ? null : (($delta / abs($from)) * 100.0);

            $direction = 'stable';
            if ($delta > 0) {
                $direction = 'increase';
            } elseif ($delta < 0) {
                $direction = 'decrease';
            }

            $trends[] = [
                'metric_name' => $metricName,
                'metric_key' => $metricKey,
                'direction' => $direction,
                'from_value' => $from,
                'to_value' => $to,
                'delta' => $delta,
                'percent_change' => $percentChange,
                'unit' => $this->resolveUnit(
                    $metricKey,
                    $latest->unit ?? ($start->unit ?? '')
                ),
                'start_date' => $start->created_at ? \Carbon\Carbon::parse($start->created_at)->format('d-m-Y') : null,
                'latest_date' => $latest->created_at ? \Carbon\Carbon::parse($latest->created_at)->format('d-m-Y') : null,
                'points' => $window->count(),
            ];
        }

        return $trends;
    }

    private function normalizeMetricKey(string $name): string
    {
        $n = strtolower(trim($name));
        if (strpos($n, 'creatinine') !== false && strpos($n, 'albumin') === false) return 'creatinine';
        if (strpos($n, 'glycosylated hemoglobin') !== false || strpos($n, 'hba1c') !== false) return 'hba1c';
        if (strpos($n, 'albumin') !== false && strpos($n, 'creatinine') !== false) return 'uacr';
        if (strpos($n, 'egfr') !== false) return 'egfr';
        if (strpos($n, 'c - reactive protein') !== false || strpos($n, 'crp') !== false) return 'crp';
        if (strpos($n, 'fbs') !== false || strpos($n, 'fasting') !== false) return 'fbs';
        if (strpos($n, 'ppbs') !== false || strpos($n, 'post') !== false) return 'ppbs';
        if (strpos($n, 'pre lunch') !== false) return 'pre_lunch';
        if (strpos($n, 'pre dinner') !== false) return 'pre_dinner';
        if (strpos($n, 'plbs') !== false) return 'plbs';
        if (strpos($n, 'pdbs') !== false) return 'pdbs';
        return 'other';
    }

    private function matchTrendRule(string $metricKey, array $rules): ?array
    {
        return $rules[$metricKey] ?? null;
    }

    private function resolveSeverity(array $trend, array $rule): ?string
    {
        $deltaAbs = abs((float) ($trend['delta'] ?? 0));
        $pctAbs = abs((float) ($trend['percent_change'] ?? 0));
        $toValue = (float) ($trend['to_value'] ?? 0);
        $fromValue = (float) ($trend['from_value'] ?? 0);
        $direction = $trend['direction'] ?? 'stable';
        $startInNormal = $this->isInNormalRange($fromValue, $rule);
        $endInNormal = $this->isInNormalRange($toValue, $rule);
        $isNormalToNormal = $startInNormal && $endInNormal;
        $normalVariationDelta = isset($rule['normal_variation_delta']) ? (float) $rule['normal_variation_delta'] : null;

        if ($isNormalToNormal && $normalVariationDelta !== null && $deltaAbs <= $normalVariationDelta) {
            return null;
        }

        if (($rule['only_direction'] ?? null) && $direction !== $rule['only_direction']) {
            return null;
        }
        if (($rule['skip_if_stable'] ?? true) && $direction === 'stable') {
            return null;
        }

        foreach (($rule['severities'] ?? []) as $severity => $condition) {
            $directionOk = !isset($condition['direction']) || $direction === $condition['direction'];
            $deltaOk = !isset($condition['min_delta']) || $deltaAbs >= (float) $condition['min_delta'];
            $pctOk = !isset($condition['min_percent']) || $pctAbs >= (float) $condition['min_percent'];
            $toOk = !isset($condition['min_to']) || $toValue >= (float) $condition['min_to'];
            $toMaxOk = !isset($condition['max_to']) || $toValue <= (float) $condition['max_to'];
            $normalOnlyOk = !isset($condition['requires_abnormal_to']) || !$condition['requires_abnormal_to'] || !$endInNormal;
            if ($directionOk && $deltaOk && $pctOk && $toOk && $toMaxOk) {
                if (!$normalOnlyOk) {
                    continue;
                }
                return $severity;
            }
        }

        return null;
    }

    private function formatStructuredFlag(array $trend, string $severity): array
    {
        $directionWord = $trend['direction'] === 'increase' ? 'increased' : ($trend['direction'] === 'decrease' ? 'decreased' : 'remained stable');
        $fromText = $this->formatNumber($trend['from_value']);
        $toText = $this->formatNumber($trend['to_value']);
        $unit = trim((string) ($trend['unit'] ?? ''));
        $unitText = $unit ? " {$unit}" : '';
        $period = $trend['start_date'] && $trend['latest_date']
            ? "from {$trend['start_date']} to {$trend['latest_date']} ({$trend['points']} test points)"
            : "{$trend['points']} test points";
        $metricName = $trend['metric_name'];
        $latestDate = $trend['latest_date'] ?: 'N/A';
        $severityText = $this->severityText($severity);

        $message = "{$metricName} {$directionWord} from {$fromText}{$unitText} to {$toText}{$unitText} (last test: {$latestDate}; period: {$period}), {$severityText}.";

        return [
            'metric' => $metricName,
            'direction' => $trend['direction'],
            'from_value' => $trend['from_value'],
            'to_value' => $trend['to_value'],
            'unit' => $unit ?: null,
            'latest_date' => $trend['latest_date'],
            'period' => $period,
            'severity' => $severity,
            'message' => $message,
        ];
    }

    private function toNumeric($value): ?float
    {
        if ($value === null) return null;
        $normalized = preg_replace('/[^0-9.\-]/', '', (string) $value);
        if ($normalized === '' || !is_numeric($normalized)) return null;
        return (float) $normalized;
    }

    private function formatNumber($value): string
    {
        if ($value === null) return 'N/A';
        $float = (float) $value;
        if (floor($float) == $float) {
            return (string) (int) $float;
        }
        return rtrim(rtrim(number_format($float, 2, '.', ''), '0'), '.');
    }

    private function severityText(string $severity): string
    {
        $map = [
            'critical' => 'critical change',
            'high' => 'high-priority change',
            'moderate' => 'moderate change',
            'info' => 'informational trend',
        ];
        return $map[$severity] ?? 'notable change';
    }

    private function isInNormalRange(float $value, array $rule): bool
    {
        $min = isset($rule['normal_min']) ? (float) $rule['normal_min'] : null;
        $max = isset($rule['normal_max']) ? (float) $rule['normal_max'] : null;

        if ($min === null && $max === null) {
            return false;
        }
        if ($min !== null && $value < $min) {
            return false;
        }
        if ($max !== null && $value > $max) {
            return false;
        }
        return true;
    }

    private function resolveUnit(string $metricKey, $rawUnit): ?string
    {
        $unit = trim((string) $rawUnit);
        if ($unit !== '') {
            return $unit;
        }

        $defaults = [
            'creatinine' => 'mg/dL',
            'hba1c' => '%',
            'uacr' => 'mg/g',
            'egfr' => 'mL/min/1.73 m2',
            'crp' => 'mg/L',
            'fbs' => 'mg/dL',
            'ppbs' => 'mg/dL',
            'plbs' => 'mg/dL',
            'pre_lunch' => 'mg/dL',
            'pre_dinner' => 'mg/dL',
            'pdbs' => 'mg/dL',
        ];

        return $defaults[$metricKey] ?? null;
    }
}
