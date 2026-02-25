<?php
$models = [
    'PatientRegistration',
    'PatientDiagnosis',
    'PatientComplication',
    'PatientVitals',
    'PatientPrescriptions',
    'PatientMedicalHistory',
    'PatientVaccination',
    'PatientVisits',
    'PatientAlerts',
    'PatientBpStatus',
    'TestResults'
];

foreach ($models as $model) {
    $file = __DIR__ . "/app/Models/{$model}.php";
    if (file_exists($file)) {
        $content = file_get_contents($file);
        if (strpos($content, 'ClearsPatientAiCache') === false) {
            // Find class declaration and insert trait
            $content = preg_replace('/class\s+'.$model.'\s+extends\s+[^{]+{/', "$0\n    use \App\Models\Traits\ClearsPatientAiCache;\n", $content);
            file_put_contents($file, $content);
            echo "Updated $model\n";
        }
    } else {
        echo "File not found: {$file}\n";
    }
}
echo "Done.\n";
