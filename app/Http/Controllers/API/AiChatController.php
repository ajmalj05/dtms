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

            // 8. Call AI API
            $response = $this->callAI($systemPrompt, $userMessage, [], 'gemini', $cachedContentName);

            if ($response['status'] === 'error') {
                return response()->json($response, 500);
            }

            // 9. Log response
            Log::channel('daily')->info('AI Chat Response', [
                'user_id' => $userId,
                'response_length' => strlen($response['response'])
            ]);

            return response()->json([
                'status' => 'success',
                'response' => $response['response'],
                'usage' => $response['usage'] ?? []
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
     * Generate patient summary and flags
     */
    public function getSummary(Request $request)
    {
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

            // Build Summary Prompt
            $systemPrompt = $this->buildSystemPrompt($context);
            $analysisPrompt = "Analyze the COMPLETE patient data for a Specialized Diabetes Hospital.\n";
            $analysisPrompt .= "REQUIRED JSON STRUCTURE:\n";
            $analysisPrompt .= "{\n";
            $analysisPrompt .= "  \"overview\": \"EXECUTIVE SUMMARY (2 Sentences). Identity (Age/Gender/Key Conditions) + Current Clinical Trajectory (Stable/Worsening/Improving). Example: '71yr Female with long-standing T2DM and Class III Obesity. Current presentation suggests worsening renal function and suboptimal glycemic control requiring intervention.',\",\n";
            $analysisPrompt .= "  \"summary\": \"PATIENT CONTEXT (The Clinical Background). Create 3 distinct bullet points covering: 1) **Disease Burden:** Primary diagnoses and duration (if known). 2) **Comorbidities:** Key active associated conditions (HTN, Dyslipidemia, etc). 3) **Care Pattern:** Recent engagement (regular vs irregular visits) and current therapy class (Insulin/OADs). Use '• ' for bullets.\",\n";
            $analysisPrompt .= "  \"flags\": [\"ACTIONABLE ALERTS & VARIATIONS (The Action). Highlight ONLY things that are WRONG or CHANGING. MUST Flag: 1) Dangerous Trends (e.g., Creatinine rose from X to Y), 2) Out-of-range Labs (Creatinine > 1.2, HbA1c > 7), 3) Missing Screenings (Foot/Eye exam overdue > 12 months), 4) Critical Variations in metabolic control.\"],\n";
            $analysisPrompt .= "  \"conclusion\": \"CONSULTANT DIRECTIVE. Provide a structured verdict.\\nStart with '<b>Assessment:</b>' [Explain the clinical status].\\nThen add '<br><b>Plan:</b>' [Specific next steps/referrals].\"\n";
            $analysisPrompt .= "}\n";
            $analysisPrompt .= "RULES: \n";
            $analysisPrompt .= "1. NO REDUNDANCY: Do not repeat facts from the Summary in the Flags section unless it is to highlight a dangerous change.\n";
            $analysisPrompt .= "2. BE AGGRESSIVE: If labs are abnormal (like Creatinine 3.8), this is a major flag, not just a summary point.\n";
            $analysisPrompt .= "3. NO Generic flags: Avoid 'Regular follow-up'. Find actual clinical gaps.\n";
            $analysisPrompt .= "4. Base clinical analysis on LONG-TERM trends provided in the full context.\n";
            $analysisPrompt .= "5. CONCLUSION must be authoritative and actionable.\n";

            // EXPERIMENTAL: Implementation of "Cached Input" via Gemini Context Caching
            $cachedContentName = $this->getOrCreateGeminiCache($patientId, $systemPrompt);

            // Call API with JSON enforcement - Using Gemini for Summaries as requested
            $response = $this->callAI($systemPrompt, $analysisPrompt, ['responseMimeType' => 'application/json'], 'gemini', $cachedContentName);
            
            if ($response['status'] === 'error') {
                return response()->json($response, 500);
            }
            
            // Clean response to ensure valid JSON
            $responseText = trim($response['response']);

            // 1. Remove markdown code blocks
            $responseText = preg_replace('/^```(?:json)?|```$/m', '', $responseText);
            $responseText = trim($responseText);

            // 2. SANITIZATION: Handle literal newlines inside JSON strings
            // This is the most common cause of "Control character error"
            $cleanedResponse = preg_replace_callback('/"([^"\\\\]*(?:\\\\.[^"\\\\]*)*)"/s', function($matches) {
                return '"' . str_replace(["\n", "\r", "\t"], ["\\n", "\\r", "\\t"], $matches[1]) . '"';
            }, $responseText);
            
            $jsonObj = json_decode($cleanedResponse, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::warning('AI Analysis JSON Decode Failed', [
                    'error' => json_last_error_msg(),
                    'raw_response' => $responseText
                ]);

                // Fallback: Ultra-robust manual field extraction
                $overview = 'N/A';
                $summary = 'N/A';
                $flags = [];

                // 1. Extract Overview (Simple)
                if (preg_match('/"overview"\s*:\s*"([\s\S]*?)"(?=\s*[,}\r\n])/i', $responseText, $ovMatches)) {
                    $overview = trim($ovMatches[1]);
                }
                
                // 2. Extract Summary (Aggressive)
                // First try to find it with a clear boundary
                if (preg_match('/"summary"\s*:\s*"([\s\S]*?)"(?=\s*[,}\r\n])/i', $responseText, $sumMatches)) {
                    $summary = trim($sumMatches[1]);
                } 
                // If that fails, try to just grab everything after "summary": " until the end of the JSON or string
                elseif (preg_match('/"summary"\s*:\s*"([\s\S]*?)$/', $responseText, $sumMatches)) {
                    $summary = rtrim(trim($sumMatches[1]), '"} ');
                }

                // 3. Extract Flags (Improved regex for quotes and bracket styles)
                if (preg_match('/"flags"\s*:\s*\[([\s\S]*?)\]/i', $responseText, $flagMatches)) {
                    $flagsStr = $flagMatches[1];
                    preg_match_all('/["\']([^"\']+)["\']/', $flagsStr, $individualFlagMatches);
                    $flags = $individualFlagMatches[1] ?? [];
                }

                // 4. Extract Conclusion
                $conclusion = 'N/A';
                if (preg_match('/"conclusion"\s*:\s*"([\s\S]*?)"(?=\s*[,}\r\n])/i', $responseText, $concMatches)) {
                    $conclusion = trim($concMatches[1]);
                } elseif (preg_match('/"conclusion"\s*:\s*"([\s\S]*?)$/', $responseText, $concMatches)) {
                    $conclusion = rtrim(trim($concMatches[1]), '"} ');
                }
                
                if (empty($flags)) {
                    Log::notice('AI Analysis - No flags found in response', ['raw' => $responseText]);
                }

                $finalData = [
                    'overview' => str_replace(['\\n', '\\"'], ["\n", '"'], $overview),
                    'summary' => str_replace(['\\n', '\\"'], ["\n", '"'], $summary),
                    'flags' => $flags,
                    'conclusion' => str_replace(['\\n', '\\"'], ["\n", '"'], $conclusion),
                    'is_error' => ($overview === 'N/A' && $summary === 'N/A') // Only error if BOTH failed
                ];
            } else {
                // Ensure all keys exist and clean up any escaped strings
                $finalData = [
                    'overview' => trim($jsonObj['overview'] ?? 'N/A'),
                    'summary' => trim($jsonObj['summary'] ?? 'N/A'),
                    'flags' => $jsonObj['flags'] ?? [],
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
        // Basic patient information
        $patient = PatientRegistration::select(
            'patient_registration.*',
            'patient_type_master.patient_type_name',
            'salutation_master.salutation_name'
        )
            ->leftJoin('patient_type_master', 'patient_type_master.id', '=', 'patient_registration.patient_type')
            ->leftJoin('salutation_master', 'salutation_master.id', '=', 'patient_registration.salutation_id')
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
                ->leftJoin('tablet_type_master', 'tablet_type_master.id', '=', 'patient_prescriptions.tablet_type_id')
                ->where('patient_prescriptions.patient_id', $patientId)
                ->orderBy('patient_prescriptions.created_at', 'desc')
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
                ->leftJoin('test_master', 'test_master.id', '=', 'test_results.TestId')
                ->where('test_results.PatientId', $patientId)
                ->orderBy('test_results.created_at', 'desc')
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
            ->get(); // Removed limit: give ALL data to AI as requested

        // Vaccinations
        $vaccinations = PatientVaccination::select(
            'patient_vaccination.*',
            'vaccination_master.vaccination_name'
        )
            ->leftJoin('vaccination_master', 'vaccination_master.id', '=', 'patient_vaccination.vaccination_id')
            ->where('patient_vaccination.patient_id', $patientId)
            ->get();

        // Alerts
        $alerts = PatientAlerts::where('patient_id', $patientId)
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
        } else {
            $val = ($scr <= 0.9) ? -0.411 : -1.209;
        }

        $egfr = 144 * pow(($scr / 0.7), $val) * pow(0.993, $age);
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
        $prompt .= "3. **FORMAT:** Use bullet points *only* when listing multiple items (Meds, Vitals, History). For simple questions, use a direct sentence.\n";
        $prompt .= "4. **CONCISENESS:** Keep responses short (2-3 lines) unless a detailed list is requested.\n";
        $prompt .= "5. **PRECISION:** Be precise. Do not summarize drug classes; list the specific medication names.";
        
        // --- ANONYMIZED DEMOGRAPHICS ---
        $prompt .= "=== DATA (ANONYMIZED) ===\n";
        // REMOVED: Name, UHID, Mobile, DOB to protect identity
        $prompt .= "Age: " . ($context['age'] ?? 'N/A') . " years\n";
        $prompt .= "Gender: " . $context['gender'] . "\n";
        $prompt .= "Blood Group: " . ($patient->blood_group_id ?? 'N/A') . "\n\n";

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
                
                if ($results->count() > 1) {
                    $prev = $results->get(1);
                    $prompt .= " [Prev: " . ($prev->ResultValue ?? 'N/A') . "]";
                    
                    // Historical range for long-term insight
                    $values = $results->pluck('ResultValue')->map(fn($v) => (float)$v)->filter();
                    if ($values->count() > 0) {
                        $prompt .= " | Hist. Range: " . $values->min() . " - " . $values->max();
                    }
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
            foreach ($context['visits'] as $visit) { // No take limit
                $prompt .= "- " . ($visit->visit_type_name ?? 'Unknown Type') . " on " . ($visit->visit_date ?? 'N/A') . "\n";
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
        $prompt .= "5. If 'Diagnosis' is empty, YOU MUST INFER it from usage of Medications (e.g., Metformin -> Diabetes, Amlodipine -> Hypertension).\n";
        $prompt .= "6. END every response with exactly this format (1 blank line, italicized): \n\n*Verify with clinical judgment.*\n";
        
        return $prompt;
    }

    private function callAI(string $systemPrompt, string $userMessage, array $configOverride = [], ?string $providerOverride = null, ?string $cachedContentName = null): array
    {
        $provider = $providerOverride ?? config('services.ai.provider', 'gemini');
        $apiKey = ($provider === 'gemini') ? config('services.gemini.api_key') : (config('services.ai.api_key') ?? config('services.gemini.api_key'));
        $model = ($provider === 'gemini') ? config('services.gemini.model') : (config('services.ai.model') ?? config('services.gemini.model'));
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
                'temperature' => 0.3,
                'topK' => 40,
                'topP' => 0.95,
                'maxOutputTokens' => 8192,
            ], $configOverride);

            $payload = [
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [
                            ['text' => ($cachedContentName ? $userMessage : ($systemPrompt . "\n\nUser Question: " . $userMessage))]
                        ]
                    ]
                ],
                'generationConfig' => $generationConfig,
            ];

            if ($cachedContentName) {
                $payload['cachedContent'] = $cachedContentName;
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
            $model = config('services.gemini.model');
            $url = "https://generativelanguage.googleapis.com/v1beta/cachedContents?key=" . $apiKey;

            $payload = [
                'model' => 'models/' . $model,
                'displayName' => "patient_history_{$patientId}",
                'systemInstruction' => [
                    'parts' => [['text' => $systemPrompt]]
                ],
                'ttl' => '3600s' // 1 hour cache
            ];

            $response = Http::timeout(30)->post($url, $payload);

            if ($response->successful()) {
                $name = $response->json()['name'] ?? null;
                if ($name) {
                    \Log::info("Gemini Context Cache Created: {$name} for Patient: {$patientId}");
                    Cache::put($cacheKey, $name, 3500); // Store name in Laravel for 58 mins
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
}
