<?php
$files = [
    __DIR__ . '/app/Http/Controllers/users/DtmsController.php',
    __DIR__ . '/app/Http/Controllers/users/MasterDataController.php',
    __DIR__ . '/app/Http/Controllers/users/BillingController.php',
    __DIR__ . '/app/Http/Controllers/users/AppointmentController.php'
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    
    $content = file_get_contents($file);
    
    // 1. Normalize existing replacements back to basic Session::get()
    $content = preg_replace('/\(request\(\)->input\([\'"]dtms_patient_id[\'"]\)\s*\?\?\s*Session::get\([\'"]dtms_pid[\'"]\)\)/', "Session::get('dtms_pid')", $content);
    $content = preg_replace('/\(request\(\)->input\([\'"]dtms_visitid[\'"]\)\s*\?\?\s*Session::get\([\'"]dtms_visitid[\'"]\)\)/', "Session::get('dtms_visitid')", $content);
    
    // 2. Standardize all Session::get('dtms_pid') and Session::get('dtms_visitid')
    $content = preg_replace('/Session::get\(\s*[\'"]dtms_pid[\'"]\s*\)/', "Session::get('dtms_pid')", $content);
    $content = preg_replace('/Session::get\(\s*[\'"]dtms_visitid[\'"]\s*\)/', "Session::get('dtms_visitid')", $content);

    $content = preg_replace('/session\(\)->get\(\s*[\'"]dtms_pid[\'"]\s*\)/', "Session::get('dtms_pid')", $content);
    $content = preg_replace('/session\(\)->get\(\s*[\'"]dtms_visitid[\'"]\s*\)/', "Session::get('dtms_visitid')", $content);
    
    // 3. Apply the global fallback replacement for any Session::get. 
    $content = str_replace("Session::get('dtms_pid')", "(request()->input('dtms_patient_id') ?? Session::get('dtms_pid'))", $content);
    $content = str_replace("Session::get('dtms_visitid')", "(request()->input('dtms_visitid') ?? Session::get('dtms_visitid'))", $content);

    // 4. Handle exists/has calls 
    $content = preg_replace('/\(\$request->has\([\'"]dtms_visitid[\'"]\)\s*\|\|\s*\$request->session\(\)->exists\([\'"]dtms_visitid[\'"]\)\)/', "\$request->session()->exists('dtms_visitid')", $content);
    $content = preg_replace('/\$request->session\(\)->exists\(\s*[\'"]dtms_visitid[\'"]\s*\)/', "(\$request->has('dtms_visitid') || \$request->session()->exists('dtms_visitid'))", $content);
    
    file_put_contents($file, $content);
    echo "Refactored " . basename($file) . "\n";
}
?>
